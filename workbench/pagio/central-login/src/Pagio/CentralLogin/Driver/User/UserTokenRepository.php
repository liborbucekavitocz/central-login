<?php namespace Pagio\CentralLogin\Driver\User;

use DB;
use Pagio\CentralLogin\User\UserTokenRepositoryInterface;

class UserTokenRepository implements UserTokenRepositoryInterface {

    public function getTableQueryBuilder()
    {
        return DB::table("user_token");
    }

    public function getToken2ByUserId($userId, $key)
    {
        return $this->getTableQueryBuilder()
            ->where("userid", "=", $userId)
            ->where("slave_key", "=", $key)
            ->orderBy("user_tokenid", "DESC")
            ->pluck("token2");
    }

    public function getTokenRowByUserId($userId, $key)
    {
        return $this->getTableQueryBuilder()
            ->where("userid", "=", $userId)
            ->where("slave_key", "=", $key)
            ->orderBy("user_tokenid", "DESC")
            ->first();
    }

    public function updateTokenRow($userId, $key, $token2, $token1IsValid = false)
    {
        $tokenRow = $this->getTokenRowByUserId($userId, $key);

        if ($tokenRow) {
            return $this->getTableQueryBuilder()
                ->where("userid", "=", $userId)
                ->where("slave_key", "=", $key)
                ->update(
                    array(
                        "token2" => $token2,
                        "token1_valid" => $token1IsValid,
                        "updated_at" => DB::raw("NOW()")
                    )
                );
        } else {
            return $this->getTableQueryBuilder()
                ->insert(
                    array(
                        "userid" => $userId,
                        "slave_key" => $key,
                        "token2" => $token2,
                        "token1_valid" => $token1IsValid,
                        "created_at" => DB::raw("NOW()"),
                        "updated_at" => DB::raw("NOW()")
                    )
                );
        }
    }

}