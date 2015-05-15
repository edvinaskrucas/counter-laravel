<?php namespace Krucas\Counter\Integration\Laravel;

use Illuminate\Support\ServiceProvider;
use Krucas\Counter\Integration\Laravel\Console\TableCommand;

class ConsoleServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('command.counter.table', function ($app) {
            return new TableCommand($app['files'], $app['composer']);
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array('command.counter.table');
    }
}
