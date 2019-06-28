<?php

namespace Larapie\Core\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Larapie\Core\Internals\LarapieManager;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * This method is part of the bootstrapping and registers the routes for ALL modules and not only for the foundation.
     *
     * @return void
     */
    public function mapWebRoutes(string $prefix, string $path)
    {
        Route::prefix($prefix)
            ->domain((new LarapieManager())->getAppUrl())
            ->middleware('web')
            ->group($path);
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * * This method is part of the bootstrapping and registers the routes for ALL modules and not only for the foundation.
     *
     * @return void
     */
    public function mapApiRoutes(string $prefix, string $path)
    {
        Route::middleware('api')
            ->domain((new LarapieManager())->getApiUrl())
            ->prefix(config('larapie.api_url') === null ? 'api/'.$prefix : $prefix)
            ->group($path);
    }
}
