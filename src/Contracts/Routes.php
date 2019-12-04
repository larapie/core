<?php

namespace Larapie\Core\Contracts;

interface Routes
{
    /**
     * Registers a route file
     * Implementing this on a Service provider in a module or package will override the base route service provider!
     *
     * @return void
     */
    public function mapRoutes(string $name, string $group, ?string $subPrefix, string $path, string $controllerNamespace);
}
