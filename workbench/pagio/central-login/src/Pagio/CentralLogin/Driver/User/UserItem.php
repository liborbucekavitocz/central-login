<?php namespace Pagio\CentralLogin\Driver\User;

use DB;
use Illuminate\Auth\UserInterface;
use Pagio\CentralLogin\User\UserItemInterface;

class UserItem implements UserInterface, UserItemInterface {
    protected $id;
    protected $login;
    protected $token;

    public function __construct($row = null)
    {
        if (!is_null($row)) {
            $this->id = array_get($row, "userid", null);
            $this->login = array_get($row, "login", null);
            $this->token = array_get($row, "remember_token", null);
        }

        return $this;
    }

    public function getAuthIdentifier()
    {
        return $this->id;
    }

    public function getAuthPassword()
    {
        return null;
    }

    public function updateRememberToken($value)
    {
        DB::table("user")
            ->where("userid", "=", $this->getId())
            ->update(
                array("remember_token" => $value)
            );

        $this->token = $value;

        return $this;
    }

    public function getLogin()
    {
        return $this->login;
    }

    /**
     * @param mixed $login
     *
     * @return
     */
    public function setLogin($login)
    {
        $this->login = $login;
        return $this;
    }

    public function getPassword()
    {
        return null;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getRememberToken()
    {
        $this->token = DB::table("user")
            ->where("userid", "=", $this->id)
            ->pluck("remember_token");

        return $this->token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }
}
