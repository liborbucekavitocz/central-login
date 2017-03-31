<?php namespace Pagio\CentralLogin\User;

interface UserRepositoryInterface {
    public function getTableQueryBuilder();

    public function getItemById($id);

    public function getItemByLogin($login);
}