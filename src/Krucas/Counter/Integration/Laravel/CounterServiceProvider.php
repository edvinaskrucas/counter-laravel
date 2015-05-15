<?php namespace Krucas\Counter\Integration\Laravel;

use Illuminate\Support\ServiceProvider;
use Krucas\Counter\Counter;

class CounterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap service provider.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes(array(
            __DIR__ . '/../../../../config/counter.php' => config_path('counter.php'),
        ), 'config');
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../../../../config/counter.php', 'counter');

        $this->app->singleton('Krucas\Counter\Integration\Laravel\DatabaseRepository', function ($app) {
            return new DatabaseRepository($app['db'], $app['config']->get('counter.table'));
        });

        $this->app->singleton('counter', function ($app) {
            $repositoryClass = $app['config']->get('counter.repository');

            return new Counter($app->make($repositoryClass));
        });

        $this->app->alias('counter', 'Krucas\Counter\Counter');
    }
}
