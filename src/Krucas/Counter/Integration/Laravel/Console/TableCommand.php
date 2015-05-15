<?php namespace Krucas\Counter\Integration\Laravel\Console;

use Illuminate\Console\Command;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Foundation\Composer;

class TableCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'counter:table';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a counter for database repository';

    /**
     * The filesystem instance.
     *
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected $files;

    /**
     * @var \Illuminate\Foundation\Composer
     */
    protected $composer;

    /**
     * Create a new queue job table command instance.
     *
     * @param \Illuminate\Contracts\Filesystem\Filesystem $files
     * @param Composer $composer
     */
    public function __construct(Filesystem $files, Composer $composer)
    {
        parent::__construct();
        $this->files = $files;
        $this->composer = $composer;
    }

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function fire()
    {
        $fullPath = $this->createBaseMigration();
        $this->files->put($fullPath, $this->files->get(__DIR__ . '/stubs/counter.stub'));
        $this->info('Migration created successfully!');
        $this->composer->dumpAutoloads();
    }

    /**
     * Create a base migration file for the table.
     *
     * @return string
     */
    protected function createBaseMigration()
    {
        $name = 'create_counter_table';
        $path = $this->laravel->databasePath() . '/migrations';
        return $this->laravel['migration.creator']->create($name, $path);
    }
}
