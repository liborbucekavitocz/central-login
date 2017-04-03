<?php namespace Pagio\CentralLogin;

use Pagio\CentralLogin\Auth\AuthDriverInterface;
use Pagio\CentralLogin\Exception\ConfigException;
use Pagio\CentralLogin\Slave\SlaveRequest;
use Pagio\CentralLogin\Slave\SlaveRequestCollectorInterface;
use Pagio\CentralLogin\Slave\SlaveRequestInterface;
use Pagio\CentralLogin\Storage\StorageContainerInterface;
use Pagio\CentralLogin\User\UserRepositoryInterface;
use Pagio\CentralLogin\User\UserTokenRepositoryInterface;

class CentralLogin {

    const PASSWORD_CHANGED = "password-changed";
    const TOKEN2_DIFF = "token2-diff";
    const TOKEN1_DIFF = "token1-diff";
    const TOKEN1_EXPIRED = "token1-expired";
    const USER_NOT_FOUND = "user-not-found";
    const LOGGED = "logged";

    /**
     * @var SlaveRequestCollectorInterface
     */
    protected $slaveRequestCollector;
    /**
     * @var AuthDriverInterface
     */
    protected $authDriver;
    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    /**
     * @var UserTokenRepositoryInterface
     */
    protected $userTokenRepository;

    /**
     * @var StorageContainerInterface
     */
    protected $storageContainer;

    protected $config;
    protected $slaves;

    public function __construct(
        AuthDriverInterface $authDriver,
        SlaveRequestCollectorInterface $slaveRequestCollector,
        UserRepositoryInterface $userRepository,
        UserTokenRepositoryInterface $userTokenRepository,
        StorageContainerInterface $storageContainer,
        array $config)
    {
        $this->slaveRequestCollector = $slaveRequestCollector;
        $this->authDriver = $authDriver;
        $this->userRepository = $userRepository;
        $this->userTokenRepository = $userTokenRepository;
        $this->storageContainer = $storageContainer;
        $this->config = $config;

        if (!array_key_exists("slave", $this->config) || empty($this->config["slave"])) {
            throw new ConfigException("Slaves aren't configured");
        }
        $this->slaves = $this->config["slave"];

    }

    /**
     * @return AuthDriverInterface
     */
    public function getAuthDriver()
    {
        return $this->authDriver;
    }

    public function getSlaveRequestCollector()
    {
        return $this->slaveRequestCollector;
    }

    public function register($login, $password)
    {
        $response = $this->getResponse($login, "register", array("password" => $password));

        $canLogIn = false;
        foreach ($response as $key => $slaveResponse) {
            if ($slaveResponse["result"] !== true) {
                continue;
            }

            $checksum = $slaveResponse["checksum"];
            $token1IsValid = false;
            $token1 = $slaveResponse["token1"];
            $token2 = $slaveResponse["token2"];

            $checksumData = array(
                $token1,
                $token2
            );

            if (!$this->checkChecksum($key, $checksum, $checksumData)) {
                continue;
            }

            if (!empty($token1)) {
                $token1IsValid = true;
                $this->storageContainer->setToken1($key, $token1);
                $canLogIn = true;
            }

            if (!($userItem = $this->userRepository->getItemByLogin($login))) {
                $userItem = $this->userRepository->create($login);
            }
            $this->userTokenRepository->updateTokenRow($userItem->getId(), $key, $token2, $token1IsValid);
        }

        if ($canLogIn) {
            $this->getAuthDriver()->loginByUsername($login, true);
        } else {
            $this->getAuthDriver()->logout();
        }


    }

    public function token()
    {
        $login = $this->getAuthDriver()->getUser()->getLogin();

        $response = $this->getResponse($login, "token");

        $canLogIn = false;
        foreach ($response as $key => $slaveResponse) {
            if ($slaveResponse["result"] !== true) {
                continue;
            }

            $checksum = $slaveResponse["checksum"];
            $status = null;
            if (array_key_exists("status", $slaveResponse)) {
                $status = $slaveResponse["status"];

                if (!$this->checkChecksum($key, $checksum, array($status))) {
                    continue;
                }

                if (in_array($status, array(self::PASSWORD_CHANGED, self::TOKEN2_DIFF))) {
                    if ($status == self::PASSWORD_CHANGED) {
                        $canLogIn = false;

                        break;
                    }
                    
                    continue;
                }
            }

            $token1IsValid = false;
            $token1 = $slaveResponse["token1"];
            $token2 = $slaveResponse["token2"];

            $checksumData = array(
                $token1,
                $token2
            );

            if (!$this->checkChecksum($key, $checksum, $checksumData)) {
                continue;
            }

            if (!empty($token1)) {
                $token1IsValid = true;
                $this->storageContainer->setToken1($key, $token1);
                $canLogIn = true;
            } else {
                $this->storageContainer->forgetToken1($key);
            }

            if (!($userItem = $this->userRepository->getItemByLogin($login))) {
                $userItem = $this->userRepository->create($login);
            }
            $this->userTokenRepository->updateTokenRow($userItem->getId(), $key, $token2, $token1IsValid);
        }

        if ($canLogIn) {
            $this->getAuthDriver()->loginByUsername($login, true);
        } else {
            $this->logout();
        }
    }

    public function login()
    {
        $login = $this->getAuthDriver()->getUser()->getLogin();

        $response = $this->getResponse($login, "login", array("token1" => "token1"));
    }

    /**
     * @param string $key
     * @param string $url
     * @param array $data
     * @param string $method
     *
     * @return self
     */
    public function addSlaveRequest($key, $url, $data, $method = SlaveRequestInterface::POST)
    {
        $slaveRequest = $this->newSlaveRequest($key, $url, $data, $method);
        $this->getSlaveRequestCollector()->addRequest($slaveRequest);

        return $this;
    }

    /**
     * @param string $key
     * @param string $url
     * @param array $data
     * @param string $method
     *
     * @return SlaveRequest
     */
    public function newSlaveRequest($key, $url, $data, $method)
    {
        return new SlaveRequest($key, $url, $data, $method);
    }

    protected function prepareDataByAction($login, $key, $action, $data = array())
    {
        $params = array();
        $checksum = array();

        $params["login"] = $login;
        switch ($action) {
            case "register":
                $password = $data["password"];

                $params["password"] = $password;
                $checksum = array(
                    $login,
                    $password
                );
                break;
            case "token":
                $userItem = $this->userRepository->getItemByLogin($login);

                $token2 = $this->userTokenRepository->getToken2ByUserId($userItem->getId(), $key);

                $params["token2"] = $token2;
                $checksum = array(
                    $login,
                    $token2
                );
                break;
            case "login":
                $userItem = $this->userRepository->getItemByLogin($login);

                $token1 = $data["token1"];
                $token2 = $this->userTokenRepository->getToken2ByUserId($userItem->getId(), $key);

                $params["token1"] = $token1;
                $params["token2"] = $token2;
                $checksum = array(
                    $login,
                    $token1,
                    $token2
                );
                break;
        }

        $params["checksum"] = $this->getChecksum($key, $checksum);

        return $params;
    }

    protected function getChecksum($key, array $data)
    {
        array_unshift($data, $this->slaves[$key]["apikey"]);
        array_push($data, $this->slaves[$key]["secret"]);

        return md5(join("", $data));
    }

    protected function getResponse($login, $action, $data = array())
    {
        foreach ($this->slaves as $key => $slave) {
            $data = $this->prepareDataByAction($login, $key, $action, $data);

            $this->addSlaveRequest($key, $slave["url"][$action], $data, SlaveRequestInterface::POST);
        }

        return $this->getSlaveRequestCollector()->run();
    }

    protected function checkChecksum($key, $checksum, $data)
    {
        return $checksum == $this->getChecksum($key, $data);
    }

    protected function logout()
    {
        $this->storageContainer->forget();
        $this->getAuthDriver()->logout();
    }

}