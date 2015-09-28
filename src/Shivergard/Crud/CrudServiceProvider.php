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
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {
        //config publish

        $publishData = array(
            __DIR__.'/Crud.php' => config_path('crud.php')
        );

        $this->publishes($publishData);
    }


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
