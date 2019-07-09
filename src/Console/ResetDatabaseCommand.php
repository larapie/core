<?php

namespace Larapie\Core\Console;

use Illuminate\Console\Command;

/**
 * Class DatabaseResetCommand.
 */
class ResetDatabaseCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'db:reset {--seed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear cache, drop all tables/collections and optionally reseed.';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $proceed = true;

        if (app()->environment('production')) {
            $this->warn('This action will drop all tables & completely wipe your cache.');
            $proceed = $this->confirm('Do you want to proceed?', false);
        }

        if ($proceed) {
            $this->reset();
        }

        $this->info('Application successfully reset!');
    }

    protected function reset()
    {
        $this->resetCache();
        $this->resetDatabase();

        if ($this->option('seed')) {
            $this->seedDatabase();
        }
    }

    protected function resetCache()
    {
        $this->line($this->call('cache:clear'));
        $this->info('cleared cache');
    }

    protected function resetDatabase()
    {
        $this->line($this->call('migrate:fresh'));
        $this->info('database reset');
    }

    protected function seedDatabase()
    {
        $this->line($this->call('db:seed'));
        $this->info('database seeded');
    }
}
