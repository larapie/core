<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 09.03.19
 * Time: 21:51.
 */

namespace Larapie\Core\Internals;

use Larapie\Core\Collections\ResourceCollection;
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

class Package extends Module
{
    public function getNamespace(): string
    {
        return config('larapie.packages.namespace') . '\\' . $this->getName();
    }
}
