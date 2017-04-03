<?php namespace Pagio\CentralLogin\Auth;

use Auth;
use DB;
use Pagio\CentralLogin\Driver\User\UserItem;
use Pagio\CentralLogin\User\UserRepositoryInterface;

class AuthDriver implements AuthDriverInterface {


    /**
     * @var UserRepositoryInterface
     */
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function loginByUsername($username, $rememberMe = true)
    {
        $userItem = $this->userRepository->getItemByLogin($username);

        if (!$userItem) {
            return null;
        }

        return Auth::loginUsingId($userItem->getId(), $rememberMe);
    }

    /**
     * @return UserItem
     */
    public function getUser()
    {
        return Auth::user();
    }

    public function check()
    {
        return Auth::check();
    }

    public function logout()
    {
        return Auth::logout();
    }
}