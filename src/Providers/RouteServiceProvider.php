<?php

namespace Larapie\Core\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;
use Larapie\Core\Contracts\Routes;
use Larapie\Core\Exceptions\InvalidRouteGroupException;

class RouteServiceProvider extends ServiceProvider implements Routes
{
    /**
     * Maps the routes for the application.
     *
     * This method is part of the bootstrapping process.
     * It will register the routes for ALL modules & packages.
     *
     * @return void
     */
    public function mapRoutes(string $name, string $group, ?string $subPrefix, string $path)
    {
        $groups = config('larapie.routing.groups', []);

        if (!array_key_exists($group, $groups)) {
            throw new InvalidRouteGroupException($group);
        }

        $middleware = $groups[$group]['middleware'] ?? [];
        $domain = $groups[$group]['domain'] ?? null;
        $prefix = $name . '/' . $this->buildPrefix($groups[$group]['prefix'] ?? null, $subPrefix);

        $route = Route::middleware($middleware);

        if (isset($domain)) {
            $route = $route->domain($domain);
        }

        if (isset($prefix)) {
            $route = $route->prefix($prefix);
        }

        $route->group($path);
    }

    protected function buildPrefix(?string $mainPrefix, ?string $subPrefix): ?string
    {
        if (isset($mainPrefix) && isset($subPrefix)) {
            return $mainPrefix . '/' . $subPrefix;
        } elseif (isset($mainPrefix) && !isset($subPrefix)) {
            return $mainPrefix;
        } elseif (!isset($mainPrefix) && isset($subPrefix)) {
            return $subPrefix;
        }

        return null;
    }
}
