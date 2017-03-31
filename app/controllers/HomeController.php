<?php

class HomeController extends \Controller {

    /**
     * @var \Pagio\CentralLogin\Auth\AuthDriverInterface
     */
    protected $authDriver;

    public function __construct(AuthDriver $authDriver)
    {
        $this->authDriver = $authDriver;
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
	    return View::make('hello');
	}

}
