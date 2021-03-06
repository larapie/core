<?php

namespace Larapie\Core\Services;

use Illuminate\Support\Collection;
use Larapie\Core\Cache\BootstrapCache;
use Larapie\Core\Collections\ModuleResourceCollection;
use Larapie\Core\Contracts\Bootstrapping;
use Larapie\Core\Support\Facades\Larapie;

class BootstrapService implements Bootstrapping
{
    protected ?array $bootstrap;

    /**
     * BootstrapService constructor.
     */
    public function __construct()
    {
        $this->boot();
    }

    protected function boot()
    {
        $result = BootstrapCache::get() ?? $this->build();
        $this->bootstrap = $result;
    }

    public function build(): array
    {
        return $this->bootstrap();
    }

    public function cache()
    {
        $this->build();
        BootstrapCache::put($this->bootstrap);
    }

    public function all(): array
    {
        return $this->bootstrap;
    }

    protected function bootstrap(): array
    {
        $bootstrap = new Collection();

        foreach (Larapie::getPackages() as $package) {
            $this->mergeResourceWithBootstrap($bootstrap, 'configs', $package->getConfigs());
            $this->mergeResourceWithBootstrap($bootstrap, 'commands', $package->getCommands());
            $this->mergeResourceWithBootstrap($bootstrap, 'events', $package->getEvents());
            $this->mergeResourceWithBootstrap($bootstrap, 'factories', $package->getFactories());
            $this->mergeResourceWithBootstrap($bootstrap, 'migrations', $package->getMigrations());
            $this->mergeResourceWithBootstrap($bootstrap, 'models', $package->getModels());
            $this->mergeResourceWithBootstrap($bootstrap, 'seeders', $package->getSeeders());
            $this->mergeResourceWithBootstrap($bootstrap, 'providers', $package->getServiceProviders());
            $this->mergeResourceWithBootstrap($bootstrap, 'routes', $package->getRoutes());
        }

        foreach (Larapie::getModules() as $module) {
            $this->mergeResourceWithBootstrap($bootstrap, 'configs', $module->getConfigs());
            $this->mergeResourceWithBootstrap($bootstrap, 'commands', $module->getCommands());
            $this->mergeResourceWithBootstrap($bootstrap, 'events', $module->getEvents());
            $this->mergeResourceWithBootstrap($bootstrap, 'factories', $module->getFactories());
            $this->mergeResourceWithBootstrap($bootstrap, 'migrations', $module->getMigrations());
            $this->mergeResourceWithBootstrap($bootstrap, 'models', $module->getModels());
            $this->mergeResourceWithBootstrap($bootstrap, 'seeders', $module->getSeeders());
            $this->mergeResourceWithBootstrap($bootstrap, 'providers', $module->getServiceProviders());
            $this->mergeResourceWithBootstrap($bootstrap, 'routes', $module->getRoutes());
        }

        return $bootstrap->toArray();
    }

    protected function mergeResourceWithBootstrap(&$bootstrap, string $resourceType, ModuleResourceCollection $resourceCollection)
    {
        $bootstrap->put($resourceType, array_merge($bootstrap->get($resourceType) ?? [], $resourceCollection->toArray()));
    }

    public function load(string $resourceType)
    {
        return $this->all()[$resourceType] ?? [];
    }

    /**
     * @return array
     */
    public function getCommands(): array
    {
        return $this->load('commands');
    }

    /**
     * @return array
     */
    public function getRoutes(): array
    {
        return $this->load('routes');
    }

    /**
     * @return array
     */
    public function getConfigs(): array
    {
        return $this->load('configs');
    }

    /**
     * @return array
     */
    public function getFactories(): array
    {
        return $this->load('factories');
    }

    /**
     * @return array
     */
    public function getMigrations(): array
    {
        return $this->load('migrations');
    }

    /**
     * @return array
     */
    public function getSeeders(): array
    {
        return $this->load('seeders');
    }

    /**
     * @return array
     */
    public function getModels(): array
    {
        return $this->load('models');
    }

    /**
     * @return array
     */
    public function getPolicies(): array
    {
        return $this->load('policies');
    }

    /**
     * @return array
     */
    public function getProviders(): array
    {
        return $this->load('providers');
    }

    /**
     * @return array
     */
    public function getEvents(): array
    {
        return $this->load('events');
    }
}
