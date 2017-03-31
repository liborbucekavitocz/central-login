<?php namespace Pagio\CentralLogin;

use Illuminate\Support\ServiceProvider;

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

		include __DIR__ . "/../../routes.php";

		$loader = \Illuminate\Foundation\AliasLoader::getInstance();

		$aliases = $loader->getAliases();

//		if (!isset($aliases["MicrositeItem"])) {
//			$loader->alias('MicrositeItem', '\Pagio\EshopMicrosite\Microsite\MicrositeItem');
//		}
//		App::singleton('MicrositeRepository', '\Pagio\EshopMicrosite\Microsite\MicrositeRepository');
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

}
