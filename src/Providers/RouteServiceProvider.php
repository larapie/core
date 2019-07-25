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
    public function mapApiRoutes(string $prefix, string $path, $auth = true)
    {
        Route::middleware('api' . ($auth ? '' : ':noauth'))
            ->domain($this->generateApiDomain())
            ->prefix(config('larapie.api_subdomain') === null ? 'api/' . $prefix : $prefix)
            ->group($path);
    }

    protected function generateApiDomain()
    {
        $url = config('larapie.api_url');

        if (($sub = config('larapie.api_subdomain')) !== null) {
            if ($url === null)
                return $sub . '.{domain}.{tld}';
            return $sub . '.' . $url;
        }

        if ($url === null)
            return '{domain}.{tld}';

        return $url;
    }
}
