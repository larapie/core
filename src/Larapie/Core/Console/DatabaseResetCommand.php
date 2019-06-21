<?php

namespace Larapie\Core\Console;

use Illuminate\Console\Command;

/**
 * Class DatabaseResetCommand.
 */
class DatabaseResetCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'larapie:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear cache, drop all tables/collections and reseed.';

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
            $this->line($this->call('cache:clear'));
            $this->info('cleared cache');

            $this->line($this->call('migrate:fresh'));
            $this->info('database reset');

            $this->line($this->call('db:seed'));
            $this->info('database seeded');
        }

        $this->info('Application successfully reset!');
    }
}
