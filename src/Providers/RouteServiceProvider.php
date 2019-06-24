<?php


namespace Larapie\Core\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

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
            ->domain(config('app.url'))
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
        $route = Route::middleware('api');
        $route->domain(config('larapie.api_url') ?? config('app.url'));
        $route->prefix($prefix = config('larapie.api_url') === null ? 'api/' . $prefix : $prefix);
        $route->namespace($namespace . '\\' . 'Api');
        $route->group($path);
    }
}

