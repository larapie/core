<?php

namespace Larapie\Core\Providers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\PackageManifest;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Larapie\Core\Console\SeedCommand;
use Larapie\Core\Contracts\Bootstrapping;
use Larapie\Core\Support\Facades\ModelFactory;
use Larapie\Core\Traits\BootstrapService;

/**
 * Class BootstrapServiceProvider.
 */
class BootstrapServiceProvider extends ServiceProvider
{
    use BootstrapService;

    public function register()
    {
        $service = $this->bootstrapService();

        $this->registerConfigs($service->getConfigs());
        $this->registerCommands($service->getCommands());
        $this->registerListeners($service->getEvents());
        $this->registerMigrations($service->getMigrations());
        $this->registerFactories($service->getFactories());
        $this->registerPolicies($service->getModels());
        $this->registerObservers($service->getModels());

        /*
         * Override the seed command with the larapi custom one.
         * This ensures that the seeders from all modules are triggered.
         *
         */
        $this->overrideSeedCommand($this->app, $service);

        /*
         * Override the package manifest with the larapi custom one.
         * This ensures that the laravel extras in the composer json's are read for all modules
         *
         */
        $this->overridePackageManifest($this->app);

        /*
         * Register all Module Service providers.
         * Always load at the end so the user has the ability to override certain functionality!
         *
         */
        $this->registerServiceProviders($service->getProviders());
    }

    protected function registerCommands(array $commands)
    {
        foreach ($commands as $command) {
            $this->commands($command['fqn']);
        }
    }

    protected function registerListeners(array $events)
    {
        collect($events)
            ->filter(function ($event) {
                return !empty($event['listeners']);
            })
            ->each(function ($event) {
                collect($event['listeners'])->each(function ($listener) use ($event) {
                    Event::listen($event['fqn'], $listener);
                });
            });
    }

    protected function registerObservers(array $models)
    {
        collect($models)
            ->filter(function ($model) {
                return !empty($model['observers']);
            })
            ->each(function ($model) {
                $modelClass = $model['fqn'];
                collect($model['observers'])->each(function ($observer) use ($modelClass) {
                    $modelClass::observe($observer);
                });
            });
    }

    protected function registerPolicies(array $models)
    {
        collect($models)
            ->filter(function ($model) {
                return $model['policy'] !== null;
            })
            ->each(function ($model) {
                Gate::policy($model['fqn'], $model['policy']);
            });
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfigs(array $configs)
    {
        collect($configs)->each(function ($config) {
            $this->mergeConfigFrom(
                $config['path'],
                $config['name']
            );
        });
    }

    /**
     * Register additional directories of factories.
     *
     * @return void
     */
    protected function registerFactories(array $factories)
    {
        collect($factories)
            ->each(function (array $factory) {
                ModelFactory::add($factory['model'], $factory['fqn']);
            });
    }

    /**
     * Register additional directories of migrations.
     *
     * @return void
     */
    protected function registerMigrations(array $migrations)
    {
        collect($migrations)
            ->each(function (array $migration) {
                $this->loadMigrationsFrom($migration['path']);
            });
    }

    protected function registerServiceProviders(array $providers)
    {
        collect($providers)
            ->each(function (array $provider) {
                $this->app->register($provider['fqn']);
            });
    }

    protected function overrideSeedCommand(\Illuminate\Contracts\Foundation\Application $app, Bootstrapping $service)
    {
        $app->extend('command.seed', function () use ($app, $service) {
            return new SeedCommand($app['db'], $service);
        });
    }

    public function overridePackageManifest(\Illuminate\Contracts\Foundation\Application $app)
    {
        $app->instance(PackageManifest::class, new \Larapie\Core\Support\Manifest\PackageManifest(
            new Filesystem(),
            $app->basePath(),
            $app->getCachedPackagesPath()
        ));
    }
}
