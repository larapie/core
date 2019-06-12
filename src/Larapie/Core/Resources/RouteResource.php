<?php


namespace Larapie\Core\Resources;

use Larapie\Core\Abstracts\Resource;

class RouteResource extends Resource
{
    public function getControllerNamespace()
    {
        return $this->getModule()->getNamespace() . str_replace('/', '\\', config('larapie.resources.controllers'));
    }

    protected function extractDataFromName(int $index): ?string
    {
        return explode('.', $this->getName())[$index] ?? null;
    }

    public function getRoutePrefix()
    {
        return $this->extractDataFromName(0);
    }

    public function getMiddlewareGroup()
    {
        return $this->extractDataFromName(1);
    }

    public function getRouteVersion()
    {
        return $this->extractDataFromName(2);
    }

    protected function getFullPrefix()
    {
        $version = $this->getRouteVersion();

        if ($version === null)
            return $this->getRoutePrefix();

        return $version . '/' . $this->getRoutePrefix();
    }

    public function toArray()
    {
        return array_merge(parent::toArray(), [
            "controller_namespace" => $this->getControllerNamespace(),
            "route_prefix" => $this->getFullPrefix(),
            "middleware_group" => $this->getMiddlewareGroup()
        ]);
    }
}