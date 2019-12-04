<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 09.03.19
 * Time: 21:51.
 */

namespace Larapie\Core\Internals;

use Larapie\Core\Collections\ResourceCollection;
use Larapie\Core\Resources\ActionResource;
use Larapie\Core\Resources\AttributeResource;
use Larapie\Core\Resources\CommandResource;
use Larapie\Core\Resources\ConfigResource;
use Larapie\Core\Resources\ControllerResource;
use Larapie\Core\Resources\EventResource;
use Larapie\Core\Resources\FactoryResource;
use Larapie\Core\Resources\JobResource;
use Larapie\Core\Resources\ListenerResource;
use Larapie\Core\Resources\MiddlewareResource;
use Larapie\Core\Resources\MigrationResource;
use Larapie\Core\Resources\ModelResource;
use Larapie\Core\Resources\NotificationResource;
use Larapie\Core\Resources\ObserverResource;
use Larapie\Core\Resources\PermissionResource;
use Larapie\Core\Resources\PolicyResource;
use Larapie\Core\Resources\ProviderResource;
use Larapie\Core\Resources\RequestResource;
use Larapie\Core\Resources\RouteResource;
use Larapie\Core\Resources\RuleResource;
use Larapie\Core\Resources\SeederResource;
use Larapie\Core\Resources\ServiceResource;
use Larapie\Core\Resources\TestResource;
use Larapie\Core\Resources\TransformerResource;

class Module
{
    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $path;

    /**
     * LarapiModule constructor.
     *
     * @param $name
     */
    public function __construct(string $name, string $path)
    {
        $this->name = $name;
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    public function getType() :string
    {
        return 'module';
    }

    public function getComposerFilePath()
    {
        return $this->getPath() . '/composer.json';
    }

    public function getNamespace(): string
    {
        return config('larapie.modules.namespace') . '\\' . $this->getName();
    }

    protected function createResourceCollection(string $subPath, string $type)
    {
        return ResourceCollection::fromPath($this->getPath() . $subPath, $this, $type);
    }

    /**
     * @return ListenerResource[] | ResourceCollection
     */
    public function getListeners()
    {
        return $this->createResourceCollection(config('larapie.resources.listeners'), ListenerResource::class);
    }

    /**
     * @return ListenerResource[] | ResourceCollection
     */
    public function getActions()
    {
        return $this->createResourceCollection(config('larapie.resources.actions'), ActionResource::class);
    }

    /**
     * @return ConfigResource[] | ResourceCollection
     */
    public function getConfigs()
    {
        return $this->createResourceCollection(config('larapie.resources.configs'), ConfigResource::class);
    }

    /**
     * @return FactoryResource[] | ResourceCollection
     */
    public function getFactories()
    {
        return $this->createResourceCollection(config('larapie.resources.factories'), FactoryResource::class);
    }

    /**
     * @return AttributeResource[] | ResourceCollection
     */
    public function getAttributes()
    {
        return $this->createResourceCollection(config('larapie.resources.attributes'), AttributeResource::class);
    }

    /**
     * @return EventResource[] | ResourceCollection
     */
    public function getEvents()
    {
        return $this->createResourceCollection(config('larapie.resources.events'), EventResource::class);
    }

    /**
     * @return RouteResource[] | ResourceCollection
     */
    public function getRoutes()
    {
        return $this->createResourceCollection(config('larapie.resources.routes'), RouteResource::class);
    }

    /**
     * @return ServiceResource[] | ResourceCollection
     */
    public function getServices()
    {
        return $this->createResourceCollection(config('larapie.resources.services'), ServiceResource::class);
    }

    /**
     * @return PolicyResource[] | ResourceCollection
     */
    public function getPolicies()
    {
        return $this->createResourceCollection(config('larapie.resources.policies'), PolicyResource::class);
    }

    /**
     * @return PermissionResource[] | ResourceCollection
     */
    public function getPermissions()
    {
        return $this->createResourceCollection(config('larapie.resources.permissions'), PermissionResource::class);
    }

    /**
     * @return TransformerResource[] | ResourceCollection
     */
    public function getTransformers()
    {
        return $this->createResourceCollection(config('larapie.resources.transformers'), TransformerResource::class);
    }

    /**
     * @return ProviderResource[] | ResourceCollection
     */
    public function getServiceProviders()
    {
        return $this->createResourceCollection(config('larapie.resources.providers'), ProviderResource::class);
    }

    /**
     * @return MigrationResource[] | ResourceCollection
     */
    public function getMigrations()
    {
        return $this->createResourceCollection(config('larapie.resources.migrations'), MigrationResource::class);
    }

    /**
     * @return ModelResource[] | ResourceCollection
     */
    public function getModels()
    {
        return $this->createResourceCollection(config('larapie.resources.models'), ModelResource::class);
    }

    /**
     * @return ObserverResource[] | ResourceCollection
     */
    public function getObservers()
    {
        return $this->createResourceCollection(config('larapie.resources.observers'), ObserverResource::class);
    }

    /**
     * @return SeederResource[] | ResourceCollection
     */
    public function getSeeders()
    {
        return $this->createResourceCollection(config('larapie.resources.seeders'), SeederResource::class);
    }

    /**
     * @return RequestResource[] | ResourceCollection
     */
    public function getRequests()
    {
        return $this->createResourceCollection(config('larapie.resources.requests'), RequestResource::class);
    }

    /**
     * @return RuleResource[] | ResourceCollection
     */
    public function getRules()
    {
        return $this->createResourceCollection(config('larapie.resources.rules'), RuleResource::class);
    }

    /**
     * @return MiddlewareResource[] | ResourceCollection
     */
    public function getMiddleWare()
    {
        return $this->createResourceCollection(config('larapie.resources.middleware'), MiddlewareResource::class);
    }

    /**
     * @return TestResource[] | ResourceCollection
     */
    public function getTests()
    {
        return $this->createResourceCollection(config('larapie.resources.tests'), TestResource::class);
    }

    /**
     * @return CommandResource[] | ResourceCollection
     */
    public function getCommands()
    {
        return $this->createResourceCollection(config('larapie.resources.commands'), CommandResource::class);
    }

    /**
     * @return NotificationResource[] | ResourceCollection
     */
    public function getNotifications()
    {
        return $this->createResourceCollection(config('larapie.resources.notifications'), NotificationResource::class);
    }

    /**
     * @return ControllerResource[] | ResourceCollection
     */
    public function getControllers()
    {
        return $this->createResourceCollection(config('larapie.resources.controllers'), ControllerResource::class);
    }

    /**
     * @return JobResource[] | ResourceCollection
     */
    public function getJobs()
    {
        return $this->createResourceCollection(config('larapie.resources.jobs'), JobResource::class);
    }
}
