<?php namespace Pagio\CentralLogin;

use Illuminate\Support\ServiceProvider;
use App;
use Auth;
use Illuminate\Auth\Guard;
use Pagio\CentralLogin\Auth\AuthHasher;
use Pagio\CentralLogin\User\UserProvider;

class CentralLoginServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->package('pagio/central-login');

        $this->auth();
        $this->routes();
        $this->aliases();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
    }

    /**
     * Registrace vlastního driveru pro přihlašování.
     *
     * @see http://toddish.co.uk/blog/creating-a-custom-laravel-4-auth-driver/
     */
    protected function auth()
    {
        Auth::extend(
            'pagiocentrallogin',
            function () {
                return new Guard(
                    new UserProvider(
                        new AuthHasher()
                    ),
                    App::make('session.store')
                );
            }
        );
    }

    protected function routes()
    {
        include __DIR__ . "/../../routes.php";
    }

    protected function aliases()
    {
        $loader = \Illuminate\Foundation\AliasLoader::getInstance();

        $aliases = $loader->getAliases();

        if (!isset($aliases["AuthDriver"])) {
            $loader->alias('AuthDriver', '\Pagio\CentralLogin\Auth\AuthDriver');
        }
        App::singleton('UserItem', '\Pagio\CentralLogin\Driver\User\UserItem');
        App::singleton('UserRepository', '\Pagio\CentralLogin\Driver\User\UserRepository');

    }

}
