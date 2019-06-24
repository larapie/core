<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 04.10.18
 * Time: 02:13.
 */

namespace Larapie\Core\Services;

use Illuminate\Support\Collection;
use Larapie\Core\Cache\BootstrapCache;
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
        $bootstrap = new Collection();

        foreach (Larapie::getModules() as $module) {
            $bootstrap->put('commands', $module->getCommands()->toArray());
            $bootstrap->put('events', $module->getEvents()->toArray());
            $bootstrap->put('routes', $module->getRoutes()->toArray());
            $bootstrap->put('configs', $module->getConfigs()->toArray());
            $bootstrap->put('factories', $module->getFactories()->toArray());
            $bootstrap->put('migrations', $module->getMigrations()->toArray());
            $bootstrap->put('models', $module->getModels()->toArray());
            $bootstrap->put('seeders', $module->getSeeders()->toArray());
            $bootstrap->put('providers', $module->getServiceProviders()->toArray());
        }

        foreach (Larapie::getPackages() as $package) {
            $bootstrap->put('commands', array_merge($bootstrap->get('commands'), $package->getCommands()->toArray()));
            $bootstrap->put('configs', array_merge($bootstrap->get('configs'), $package->getConfigs()->toArray()));
            $bootstrap->put('providers', array_merge($bootstrap->get('providers'), $package->getServiceProviders()->toArray()));
        }

        return $bootstrap->toArray();
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
