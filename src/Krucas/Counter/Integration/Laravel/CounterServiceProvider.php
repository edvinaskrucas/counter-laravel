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

        $this->app->singleton('counter', function ($app) {
            /** @var \Krucas\Counter\Integration\Laravel\RepositoryManager $manager */
            $manager = $app['counter.repository.manager'];

            return new Counter($manager->driver($app['config']->get('counter.driver')));
        });

        $this->app->singleton('counter.repository.manager', function ($app) {
            return new RepositoryManager($app);
        });

        $this->app->alias('counter', 'Krucas\Counter\Counter');
        $this->app->alias('counter.repository.manager', 'Krucas\Counter\Integration\Laravel\RepositoryManager');
    }
}
