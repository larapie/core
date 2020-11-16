<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 09.03.19
 * Time: 21:51.
 */

namespace Larapie\Core\Internals;

use Larapie\Core\Abstracts\Resource;
use Larapie\Core\Collections\ModuleResourceCollection;
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

    public function getType(): string
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

    /**
     * @param string|Resource $type Resource classname
     * @return ModuleResourceCollection
     */
    public function createResourceCollection(string $type)
    {
        return ModuleResourceCollection::fromPath($this->getPath() . $type::configPath(), $this, $type);
    }

    /**
     * @return ListenerResource[] | ModuleResourceCollection
     */
    public function getActions()
    {
        return $this->createResourceCollection(ActionResource::class);
    }

    /**
     * @return ConfigResource[] | ModuleResourceCollection
     */
    public function getConfigs()
    {
        return $this->createResourceCollection(ConfigResource::class);
    }

    /**
     * @return FactoryResource[] | ModuleResourceCollection
     */
    public function getFactories()
    {
        return $this->createResourceCollection(FactoryResource::class);
    }

    /**
     * @return AttributeResource[] | ModuleResourceCollection
     */
    public function getAttributes()
    {
        return $this->createResourceCollection(AttributeResource::class);
    }

    /**
     * @return ListenerResource[] | ModuleResourceCollection
     */
    public function getListeners()
    {
        return $this->createResourceCollection(ListenerResource::class);
    }

    /**
     * @return EventResource[] | ModuleResourceCollection
     */
    public function getEvents()
    {
        return $this->createResourceCollection(EventResource::class);
    }

    /**
     * @return RouteResource[] | ModuleResourceCollection
     */
    public function getRoutes()
    {
        return $this->createResourceCollection(RouteResource::class);
    }

    /**
     * @return ServiceResource[] | ModuleResourceCollection
     */
    public function getServices()
    {
        return $this->createResourceCollection(ServiceResource::class);
    }

    /**
     * @return PolicyResource[] | ModuleResourceCollection
     */
    public function getPolicies()
    {
        return $this->createResourceCollection(PolicyResource::class);
    }

    /**
     * @return PermissionResource[] | ModuleResourceCollection
     */
    public function getPermissions()
    {
        return $this->createResourceCollection(PermissionResource::class);
    }

    /**
     * @return TransformerResource[] | ModuleResourceCollection
     */
    public function getTransformers()
    {
        return $this->createResourceCollection(TransformerResource::class);
    }

    /**
     * @return ProviderResource[] | ModuleResourceCollection
     */
    public function getServiceProviders()
    {
        return $this->createResourceCollection(ProviderResource::class);
    }

    /**
     * @return MigrationResource[] | ModuleResourceCollection
     */
    public function getMigrations()
    {
        return $this->createResourceCollection(MigrationResource::class);
    }

    /**
     * @return ModelResource[] | ModuleResourceCollection
     */
    public function getModels()
    {
        return $this->createResourceCollection(ModelResource::class);
    }

    /**
     * @return ObserverResource[] | ModuleResourceCollection
     */
    public function getObservers()
    {
        return $this->createResourceCollection(ObserverResource::class);
    }

    /**
     * @return SeederResource[] | ModuleResourceCollection
     */
    public function getSeeders()
    {
        return $this->createResourceCollection(SeederResource::class);
    }

    /**
     * @return RequestResource[] | ModuleResourceCollection
     */
    public function getRequests()
    {
        return $this->createResourceCollection(RequestResource::class);
    }

    /**
     * @return RuleResource[] | ModuleResourceCollection
     */
    public function getRules()
    {
        return $this->createResourceCollection(RuleResource::class);
    }

    /**
     * @return MiddlewareResource[] | ModuleResourceCollection
     */
    public function getMiddleWare()
    {
        return $this->createResourceCollection(MiddlewareResource::class);
    }

    /**
     * @return TestResource[] | ModuleResourceCollection
     */
    public function getTests()
    {
        return $this->createResourceCollection(TestResource::class);
    }

    /**
     * @return CommandResource[] | ModuleResourceCollection
     */
    public function getCommands()
    {
        return $this->createResourceCollection(CommandResource::class);
    }

    /**
     * @return NotificationResource[] | ModuleResourceCollection
     */
    public function getNotifications()
    {
        return $this->createResourceCollection(NotificationResource::class);
    }

    /**
     * @return ControllerResource[] | ModuleResourceCollection
     */
    public function getControllers()
    {
        return $this->createResourceCollection(ControllerResource::class);
    }

    /**
     * @return JobResource[] | ModuleResourceCollection
     */
    public function getJobs()
    {
        return $this->createResourceCollection(JobResource::class);
    }
}
