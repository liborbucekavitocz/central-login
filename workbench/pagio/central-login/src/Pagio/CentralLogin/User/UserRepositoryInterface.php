<?php namespace Pagio\CentralLogin\User;

interface UserRepositoryInterface {
    public function getItemById($id);
    public function getItemByLogin($login);
    public function create($login);
}