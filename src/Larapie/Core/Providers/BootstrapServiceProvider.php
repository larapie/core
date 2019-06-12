<?php

namespace Larapie\Core\Providers;

use App\Foundation\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Larapie\Core\Console\SeedCommand;
use Larapie\Core\Services\BootstrapService;

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

        /* Override the seed command with the larapi custom one */
        $this->overrideSeedCommand($this->app, $service);

        /*
         * Register all Module Service providers.
         * Always load at the end so the user has the ability to override certain functionality!
         *
         */
        $this->registerServiceProviders($service->getProviders());
    }

    protected function loadService()
    {
        $service = new BootstrapService();

        if (!($this->app->environment('production'))) {
            $service->reload();
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

    protected function registerRoutes(array $routes)
    {
        foreach ($routes as $route) {
            if (class_exists(config('larapie.providers.routing'))) {
                $provider = new RouteServiceProvider($this->app);
                $method = 'map'.ucfirst(strtolower($route['middleware_group']).'Routes');

                if (method_exists($provider, $method)) {
                    $provider->$method($route['route_prefix'], $route['path'], $route['controller_namespace']);
                    continue;
                }
            }
            Route::prefix($route['route_prefix'])
                ->middleware($route['middleware_group'])
                ->namespace($route['controller_namespace'])
                ->group($route['path']);
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
                $qsdg = '';
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

    protected function overrideSeedCommand(\Illuminate\Contracts\Foundation\Application $app, BootstrapService $service)
    {
        $app->extend('command.seed', function () use ($app, $service) {
            return new SeedCommand($app['db'], $service);
        });
    }
}
