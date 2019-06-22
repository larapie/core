<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 04.10.18
 * Time: 02:13.
 */

namespace Larapie\Core\Services;

use Larapie\Core\Cache\BootstrapCache;
use Larapie\Core\Collections\ResourceCollection;
use Larapie\Core\Contracts\Bootstrapping;
use Larapie\Core\Support\Facades\Larapie;

class BootstrapService implements Bootstrapping
{
    protected $bootstrap;

    protected function boot(bool $fromCache = false)
    {
        if ($fromCache && ($bootstrap = BootstrapCache::get()) !== null) {
            $this->bootstrap = $bootstrap;
        } else {
            $this->cache();
        }
    }

    public function cache()
    {
        $this->bootstrap = $this->bootstrap();
        BootstrapCache::put($this->bootstrap);
    }

    protected function isBooted(): bool
    {
        return $this->bootstrap !== null;
    }

    public function all(): array
    {
        if (!$this->isBooted()) {
            $this->boot(false);
        }

        return $this->bootstrap;
    }

    protected function bootstrap()
    {
        $bootstrap = [];
        foreach (Larapie::getModules() as $module) {
            $bootstrap['commands'] = $this->bootstrapResource($module->getCommands());
            $bootstrap['events'] = $this->bootstrapResource($module->getEvents());
            $bootstrap['routes'] = $this->bootstrapResource($module->getRoutes());
            $bootstrap['configs'] = $this->bootstrapResource($module->getConfigs());
            $bootstrap['factories'] = $this->bootstrapResource($module->getFactories());
            $bootstrap['migrations'] = $this->bootstrapResource($module->getMigrations());
            $bootstrap['models'] = $this->bootstrapResource($module->getModels());
            $bootstrap['seeders'] = $this->bootstrapResource($module->getSeeders());
            $bootstrap['providers'] = $this->bootstrapResource($module->getServiceProviders());
        }

        foreach (Larapie::getPackages() as $package) {
            $bootstrap['commands'] = array_merge($bootstrap['commands'] ?? [], $this->bootstrapResource($package->getCommands()));
            $bootstrap['configs'] = array_merge($bootstrap['configs'] ?? [], $this->bootstrapResource($package->getConfigs()));
            $bootstrap['providers'] = array_merge($bootstrap['providers'] ?? [], $this->bootstrapResource($package->getServiceProviders()));
        }

        return $bootstrap;
    }

    protected function bootstrapResource(ResourceCollection $commands)
    {
        return $commands->map(function ($item, $key) {
            return $item->toArray();
        })->all();
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
