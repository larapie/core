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
        $bootstrap = new Collection();

        foreach (Larapie::getPackages() as $package) {
            $this->mergeResourceWithBootstrap($bootstrap, 'commands', $package->getCommands());
            $this->mergeResourceWithBootstrap($bootstrap, 'configs', $package->getConfigs());
            $this->mergeResourceWithBootstrap($bootstrap, 'providers', $package->getServiceProviders());
            $this->mergeResourceWithBootstrap($bootstrap, 'migrations', $package->getMigrations());
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

    protected function mergeResourceWithBootstrap(&$bootstrap, string $resourceType, ResourceCollection $resourceCollection)
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
