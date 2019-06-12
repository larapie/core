<?php

namespace Larapie\Core\Console;

use Illuminate\Console\Command;
use Larapie\Core\LarapieServiceProvider;

class InstallLarapieCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'larapie:install';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Start a fresh larapie installation.';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->warn('This will replace you current .env file and reseed the database.');
        $this->warn('Only do this if you are installing larapie for the first time');
        $proceed = $this->confirm("Do you want to proceed?", false);

        if ($proceed) {
            $this->info($this->copyEnvFile());
            $this->info($this->installMergePlugin());
            $this->info($this->composerInstall());
            $this->info($this->generateAppKey());
            $this->info($this->publishAssets());
            $this->info($this->seedDatabase());
        }
    }

    public function copyEnvFile()
    {
        return exec('php -r "file_exists(\'.env\') || copy(\'.env.example\', \'.env\');"');
    }

    public function generateAppKey()
    {
        return $this->call('key:generate');
    }

    public function installMergePlugin()
    {
        return exec('composer require wikimedia/composer-merge-plugin');
    }

    public function publishAssets()
    {
        $this->call('vendor:publish', [
            '--provider' => LarapieServiceProvider::class,
        ]);
    }

    public function composerInstall()
    {
        return exec('composer install');
    }

    public function seedDatabase()
    {
        return $this->call('db:seed');
    }

}
