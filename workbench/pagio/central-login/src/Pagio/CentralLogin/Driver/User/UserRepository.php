<?php namespace Pagio\CentralLogin\Driver\User;

use Pagio\CentralLogin\User\UserRepositoryInterface;
use DB;

class UserRepository implements UserRepositoryInterface {

    public function getTableQueryBuilder()
    {
        return DB::table("user");
    }

    public function getItemById($id)
    {
        $row = $this->getTableQueryBuilder()
            ->where("userid", "=", $id)
            ->first();

        return $this->item($row);
    }

    public function getItemByLogin($login)
    {
        $row = $this->getTableQueryBuilder()
            ->where("login", "=", $login)
            ->first();
        
        return $this->item($row);
    }

    protected function items($rows)
    {
        $items = array();
        foreach ($rows as $row) {
            array_push($items, $this->item($row));
        }

        return $items;
    }

    protected function item($row)
    {
        if ($row) {
            return new UserItem((array) $row);
        }

        return null;
    }

}