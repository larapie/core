<?php

namespace Larapie\Core\Providers;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\PackageManifest;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Larapie\Core\Console\SeedCommand;
use Larapie\Core\Larapie\Core\Contracts\Bootstrapping;

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

        $this->registerCommands($service->getCommands());
        $this->registerListeners($service->getEvents());
        $this->registerRoutes($service->getRoutes());
        $this->registerMigrations($service->getMigrations());
        $this->registerConfigs($service->getConfigs());
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
        foreach ($events as $event) {
            if (!empty($listeners = $event['listeners'])) {
                foreach ($listeners as $listener) {
                    Event::listen($event['fqn'], $listener);
                }
            }
        }
    }

    protected function registerObservers(array $models)
    {
        foreach ($models as $model) {
            if (!empty($observers = $model['observers'])) {
                foreach ($observers as $observer) {
                    $modelClass = $model['fqn'];
                    $modelClass::observe($observer);
                }
            }
        }
    }

    protected function registerPolicies(array $models)
    {
        foreach ($models as $model) {
            if (($policy = $model['policy']) !== null) {
                Gate::policy($model['fqn'], $policy);
            }
        }
    }

    protected function registerRoutes(array $routes)
    {
        foreach ($routes as $route) {
            if (class_exists($providerClass = config('larapie.providers.routing')) && ($middleware = $route['middleware_group']) !== null) {
                $provider = new $providerClass($this->app);
                $method = 'map' . ucfirst(strtolower($route['middleware_group']) . 'Routes');

                if (method_exists($provider, $method)) {
                    $provider->$method($route['route_prefix'], $route['path'], $route['controller_namespace']);
                    continue;
                }
            }

            $routes = Route::prefix($route['route_prefix']);

            if ($middleware !== null)
                $routes->middleware($route['middleware_group']);

            if (($controller = $route['controller_namespace']) !== null)
                $routes->namespace($controller);

            $routes->group($route['path']);
        }
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfigs(array $configs)
    {
        foreach ($configs as $config) {
            $this->mergeConfigFrom(
                $config['path'],
                $config['name']
            );
        }
    }

    /**
     * Register additional directories of factories.
     *
     * @return void
     */
    protected function registerFactories(array $factories)
    {
        foreach ($factories as $factory) {
            if (!$this->app->environment('production')) {
                $factoryClass = app(\Illuminate\Database\Eloquent\Factory::class);
                $factoryClass->load($factory['directory']);
            }
        }
    }

    /**
     * Register additional directories of migrations.
     *
     * @return void
     */
    protected function registerMigrations(array $migrations)
    {
        foreach ($migrations as $migration) {
            $this->loadMigrationsFrom($migration['path']);
        }
    }

    protected function registerServiceProviders(array $providers)
    {
        foreach ($providers as $provider) {
            $this->app->register($provider['fqn']);
        }
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
            new Filesystem(), $app->basePath(), $app->getCachedPackagesPath()
        ));
    }
}
