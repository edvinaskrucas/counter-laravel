<?php namespace Krucas\Counter\Integration\Laravel;

use Illuminate\Support\Manager;
use Krucas\Counter\ArrayRepository;

class RepositoryManager extends Manager
{
    /**
     * Create database driver.
     *
     * @return \Krucas\Counter\Integration\Laravel\DatabaseRepository
     */
    protected function createDatabaseDriver()
    {
        /** @var \Illuminate\Contracts\Config\Repository $config */
        $config = $this->app['config'];

        return new DatabaseRepository(
            $this->app['db']->connection($config->get('counter.database.connection')),
            $config->get('counter.database.table')
        );
    }

    /**
     * Create array repository.
     *
     * @return \Krucas\Counter\ArrayRepository
     */
    protected function createArrayDriver()
    {
        return new ArrayRepository();
    }

    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        $this->app['config']->get('counter.driver');
    }
}
