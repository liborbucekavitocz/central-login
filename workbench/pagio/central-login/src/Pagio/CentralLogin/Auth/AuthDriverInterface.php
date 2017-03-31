<?php namespace Pagio\CentralLogin\Auth;

interface AuthDriverInterface {
    public function loginByUsername($username, $rememberMe = true);
    public function getUser();
    public function check();
    public function logout();
}