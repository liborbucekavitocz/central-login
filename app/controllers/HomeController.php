<?php

class HomeController extends \Controller {

    protected $centralLogin;

    public function __construct()
    {
        $config = \Config::get("central-login::config");
        $this->centralLogin = new \Pagio\CentralLogin\CentralLogin(
            App::make("AuthDriver"),
            new \Pagio\CentralLogin\Slave\SlaveRequestCollector(), new \Pagio\CentralLogin\Driver\User\UserRepository(),
            new \Pagio\CentralLogin\Driver\User\UserTokenRepository(),
            new \Pagio\CentralLogin\Storage\StorageContainer(new \Pagio\CentralLogin\Storage\SessionRepository()),
            $config
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Default Home Controller
    |--------------------------------------------------------------------------
    |
    | You may wish to use controllers instead of, or in addition to, Closure
    | based routes. That's great! Here is an example controller method to
    | get you started. To route to this controller, just add the route:
    |
    |	Route::get('/', 'HomeController@showWelcome');
    |
    */

    public function showWelcome()
    {
//        $this->centralLogin->register("buca92", "123456");
        $this->centralLogin->token();


        return View::make('hello');
    }

}
