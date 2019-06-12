<?php


namespace Larapie\Core;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Foundation\PackageManifest;
use Illuminate\Support\ServiceProvider;
use Larapie\Core\Console\InstallLarapieCommand;
use Larapie\Core\Console\ReloadBootstrapCommand;
use Larapie\Core\Console\UpdateLarapieCommand;
use Larapie\Core\Internals\LarapieManager;
use Larapie\Core\Providers\BootstrapServiceProvider;
use Larapie\Core\Support\Facades\Larapie;

class LarapieServiceProvider extends ServiceProvider
{

    protected $commands = [
        InstallLarapieCommand::class,
        UpdateLarapieCommand::class,
        ReloadBootstrapCommand::class
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
        $this->overridePackageManifest();

        $this->registerBootstrapServiceProvider();
    }

    public function overridePackageManifest()
    {
        $this->app->instance(PackageManifest::class, new Support\Manifest\PackageManifest(
            new Filesystem, $this->app->basePath(), $this->app->getCachedPackagesPath()
        ));
    }

    public function registerLarapieAlias()
    {
        app()->singleton('larapie', function () {
            return new LarapieManager();
        });

        $this->app->alias("larapie", Larapie::class);
    }

    public function registerConfig()
    {
        $this->mergeConfigFrom(__DIR__ . '/Config/larapie.php', 'larapie');
    }

    public function publishConfig()
    {
        $this->publishes([
            __DIR__ . '/Config/larapie.php' => config_path('larapie.php'),
        ]);
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