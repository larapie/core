<?php

namespace Larapie\Core;

use Illuminate\Support\ServiceProvider;
use Larapie\Core\Console\CacheBootstrapCommand;
use Larapie\Core\Console\InstallLarapieCommand;
use Larapie\Core\Console\ResetDatabaseCommand;
use Larapie\Core\Console\UpdateLarapieCommand;
use Larapie\Core\Contracts\Bootstrapping;
use Larapie\Core\Internals\LarapieManager;
use Larapie\Core\Providers\BootstrapServiceProvider;
use Larapie\Core\Services\BootstrapService;
use Larapie\Core\Support\Facades\Larapie;
use Larapie\Core\Support\Facades\ModelFactory;

class LarapieServiceProvider extends ServiceProvider
{
    protected $commands = [
        InstallLarapieCommand::class,
        UpdateLarapieCommand::class,
        CacheBootstrapCommand::class,
        ResetDatabaseCommand::class,
    ];

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishConfig();
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerLarapieAlias();
        $this->registerConfig();
        $this->registerCommands();

        $this->registerBootstrapService();
        $this->registerBootstrapServiceProvider();
    }

    public function registerLarapieAlias()
    {
        app()->singleton('larapie', function () {
            return new LarapieManager();
        });

        $this->app->alias('larapie', Larapie::class);

        app()->singleton('modelfactoy', function () {
            return new ModelFactory();
        });

        $this->app->alias('modelfactory', ModelFactory::class);
    }

    protected function registerConfig()
    {
        $this->mergeConfigFrom(__DIR__.'/Config/larapie.php', 'larapie');
    }

    protected function publishConfig()
    {
        $this->publishes([
            __DIR__.'/Config/larapie.php' => config_path('larapie.php'),
        ]);
    }

    protected function registerBootstrapService()
    {
        $this->app->singleton(Bootstrapping::class, function ($app) {
            return new BootstrapService();
        });
    }

    protected function registerBootstrapServiceProvider()
    {
        $this->app->register(BootstrapServiceProvider::class);
    }

    protected function registerCommands()
    {
        $this->commands($this->commands);
    }
}
