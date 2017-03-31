<?php namespace Pagio\CentralLogin\User;

interface UserItemInterface {
    public function getAuthIdentifier();

    public function getAuthPassword();

    public function updateRememberToken($value);

    public function getLogin();

    public function setLogin($login);

    public function getPassword();

    public function getId();

    public function getRememberToken();

    public function setRememberToken($value);

    public function getRememberTokenName();
}