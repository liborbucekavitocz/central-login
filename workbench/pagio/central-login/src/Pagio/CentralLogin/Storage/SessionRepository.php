<?php  namespace Pagio\CentralLogin\Storage;

use Session;

class SessionRepository implements StorageRepositoryInterface {

    protected $sessionKey = 'Pagio\CentralLogin\Storage\StorageRepositoryInterface';

    public function forget()
    {
        return Session::forget($this->sessionKey);
    }

    public function forgetToken1($key)
    {
        return Session::forget($this->sessionKey . ".token." . $key);
    }

    public function setToken1($key, $token1)
    {
        $data = array(
            "token1" => $token1,
            "guid" => uniqid(),
            "slave_key" => $key
        );

        return Session::put($this->sessionKey . ".token." . $key, $data);
    }

    public function getToken1($key)
    {
        return Session::get($this->sessionKey . ".token." . $key, null);
    }

}