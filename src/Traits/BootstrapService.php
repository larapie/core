<?php


namespace Larapie\Core\Traits;


use Larapie\Core\Contracts\Bootstrapping;

trait BootstrapService
{
    protected function bootstrapService(): Bootstrapping
    {
        $service = $this->app->make(Bootstrapping::class);

        if (!($this->app->environment('production'))) {
            $service->cache();
        }

        return $service;
    }
}
