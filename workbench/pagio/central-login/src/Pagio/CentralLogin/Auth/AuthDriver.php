<?php namespace Pagio\CentralLogin\Auth;

use Auth;
use DB;

class AuthDriver implements AuthDriverInterface {

    public function loginByUsername($username, $rememberMe = true)
    {
        $id = DB::table("user")
            ->where("login", "=", $username)
            ->pluck("userid");

        if (!$id) {
            return null;
        }

        return Auth::loginUsingId($id, $rememberMe);
    }

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