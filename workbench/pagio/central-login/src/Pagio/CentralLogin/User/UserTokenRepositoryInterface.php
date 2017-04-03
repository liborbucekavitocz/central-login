<?php namespace Pagio\CentralLogin\User;

interface UserTokenRepositoryInterface {
    public function getToken2ByuserId($userId, $key);
}