<?php

namespace Larapie\Core\Providers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\PackageManifest;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Larapie\Core\Console\SeedCommand;
use Larapie\Core\Contracts\Bootstrapping;
use Larapie\Core\Contracts\Routes;
use Larapie\Core\Exceptions\BootstrappingFailedException;

/**
 * Class BootstrapServiceProvider.
 */
class BootstrapServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        $service = $this->loadService();

        $this->registerConfigs($service->getConfigs());
        $this->registerCommands($service->getCommands());
        $this->registerListeners($service->getEvents());
        $this->registerMigrations($service->getMigrations());
        $this->registerFactories($service->getFactories());
        $this->registerPolicies($service->getModels());
        $this->registerObservers($service->getModels());
        $this->registerRoutes($service->getRoutes());

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

    protected function loadService(): Bootstrapping
    {
        $service = $this->app->make(Bootstrapping::class);

        if (!($this->app->environment('production'))) {
            $service->cache();
        }

        return $service;
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

    protected function getRouteProvider(?string $provider): Routes
    {
        if ($provider !== null) {
            return new $provider($this->app);
        }

        return class_exists($providerClass = config('larapie.routing.provider')) ? new $providerClass($this->app) : new RouteServiceProvider($this->app);
    }

    protected function registerRoutes(array $routes)
    {
        collect($routes)
            ->filter(function (array $route) {
                return !is_bool($route);
            })
            ->each(function (array $route) {
                if ($route['route_group'] !== null) {
                    $this->getRouteProvider($route['route_provider'])->mapRoutes($route['route_name'], $route['route_group'], $route['route_prefix'], $route['path']);

                    return;
                }

                throw new BootstrappingFailedException("Registering Route < $route > failed. No group was provided. Use the following format name.group.prefix");
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
                //REGISTER FACTORIES HERE
/*                tap(app(\Illuminate\Database\Eloquent\Factory::class), function ($eloquentFactory) use ($factory) {
                    $eloquentFactory->load($factory['directory']);
                });*/
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
