<?php namespace Shivergard\Crud;

use Illuminate\Support\ServiceProvider;
use Shivergard\Crud\Console\CrudMake;
use Shivergard\Crud\Console\CrudInit;

class CrudServiceProvider extends ServiceProvider {

	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->commands('Shivergard\Crud\Console\CrudMake');
		$this->commands('Shivergard\Crud\Console\CrudInit');
	}


	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [];
	}

}
