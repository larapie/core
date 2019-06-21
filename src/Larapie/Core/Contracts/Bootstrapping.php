<?php


namespace Larapie\Core\Larapie\Core\Contracts;


interface Bootstrapping
{
    public function cache();

    public function all();

    public function load(string $resourceType);

    public function getCommands(): array;

    public function getRoutes(): array;

    public function getConfigs(): array;

    public function getFactories(): array;

    public function getMigrations(): array;

    public function getSeeders(): array;

    public function getModels(): array;

    public function getPolicies(): array;

    public function getProviders(): array;

    public function getEvents(): array;
}
