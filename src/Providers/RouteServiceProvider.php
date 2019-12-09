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
        $prefix = $this->buildPrefix($groups[$group]['prefix'] ?? null, $subPrefix).'/'.$name;

        $route = Route::middleware($middleware);

        if (isset($domain)) {
            $route = $route->domain($this->buildDomain($domain));
        }

        if (isset($prefix)) {
            $route = $route->prefix($prefix);
        }

        $route->group($path);
    }

    protected function buildPrefix(?string $mainPrefix, ?string $subPrefix): ?string
    {
        if (isset($mainPrefix) && isset($subPrefix)) {
            return $mainPrefix.'/'.$subPrefix;
        } elseif (isset($mainPrefix) && !isset($subPrefix)) {
            return $mainPrefix;
        } elseif (!isset($mainPrefix) && isset($subPrefix)) {
            return $subPrefix;
        }

        return null;
    }

    protected function buildDomain(string $input) :string
    {
        // in case scheme relative URI is passed, e.g., //www.google.com/
        $input = trim($input, '/');

        // If scheme not included, prepend it
        if (!preg_match('#^http(s)?://#', $input)) {
            $input = 'http://'.$input;
        }

        $urlParts = parse_url($input);

        return $urlParts['host'];
    }
}
