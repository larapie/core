<?php

namespace Larapie\Core\Console;

use Illuminate\Console\Command;

class UpdateLarapieCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'larapie:update {--git}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the packages (optional pull from git).';

    /**
     * Execute the console command.
     *
     * @return void
     */
    public function handle()
    {
        $this->warn('This will update your packages to the latest version (according to your composer lock) and pull the latest changes.');
        $proceed = $this->confirm('Do you want to proceed?', false);

        if ($proceed) {
            $this->info($this->enableMaintenanceMode());
            if ($this->option('git')) {
                $this->info($this->gitPull());
            }
            $this->info($this->composerInstall());
            $this->info($this->bootstrap());
            $this->info($this->disableMaintenanceMode());
        }
    }

    public function enableMaintenanceMode()
    {
        return $this->call('down');
    }

    public function disableMaintenanceMode()
    {
        return $this->call('up');
    }

    public function composerInstall()
    {
        return exec('composer install');
    }

    public function gitPull()
    {
        return exec('git pull');
    }

    public function bootstrap()
    {
        return $this->call('larapie:bootstrap');
    }
}
