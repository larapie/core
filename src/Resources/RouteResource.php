<?php

namespace Larapie\Core\Resources;

use Larapie\Core\Abstracts\Resource;

class RouteResource extends Resource
{
    public function getControllerNamespace()
    {
        return $this->getModule()->getNamespace().str_replace('/', '\\', config('larapie.resources.controllers'));
    }

    protected function extractDataFromName(int $index): ?string
    {
        return explode('.', $this->getName())[$index] ?? null;
    }

    public function getRoutePrefix()
    {
        return $this->extractDataFromName(2);
    }

    public function getGroup()
    {
        return $this->extractDataFromName(1);
    }

    protected function getRouteName()
    {
        return $this->extractDataFromName(0);
    }

    protected function getRouteServiceProvider(): ?string
    {
        foreach ($this->getModule()->getServiceProviders() as $provider) {
            if ($provider->hasRoutes()) {
                return $provider->getFQN();
            }
        }

        return null;
    }

    public function toArray()
    {
        return array_merge(parent::toArray(), [
            'controller_namespace' => $this->getControllerNamespace(),
            'route_name'           => $this->getRouteName(),
            'route_prefix'         => $this->getRoutePrefix(),
            'route_group'          => $this->getGroup(),
            'route_provider'       => $this->getRouteServiceProvider(),
        ]);
    }
}
