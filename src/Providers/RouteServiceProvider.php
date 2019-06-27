<?php

namespace Larapie\Core\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Larapie\Core\Support\Facades\Larapie;

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
    public function mapWebRoutes(string $prefix, string $path, string $namespace)
    {
        Route::prefix($prefix)
            ->domain(Larapie::getAppUrl())
            ->middleware('web')
            ->namespace($namespace . '\\' . 'Web')
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
    public function mapApiRoutes(string $prefix, string $path, string $namespace)
    {
        Route::middleware('api')
            ->domain(Larapie::getApiUrl())
            ->prefix(config('larapie.api_url') === null ? 'api/' . $prefix : $prefix)
            ->namespace($namespace . '\\' . 'Api')
            ->group($path);
    }
}
