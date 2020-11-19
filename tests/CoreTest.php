<?php

namespace Tests;

use Illuminate\Support\Facades\Config;
use Larapie\Core\Base\Controller;
use Larapie\Core\LarapieServiceProvider;
use Larapie\Core\Resolvers\FQNResolver;
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
use Larapie\Core\Resources\RepositoryResource;
use Larapie\Core\Resources\RequestResource;
use Larapie\Core\Resources\RouteResource;
use Larapie\Core\Resources\RuleResource;
use Larapie\Core\Resources\SeederResource;
use Larapie\Core\Resources\ServiceResource;
use Larapie\Core\Resources\TestResource;
use Larapie\Core\Resources\TransformerResource;
use Larapie\Core\Support\Facades\Larapie;
use Orchestra\Testbench\TestCase;

class CoreTest extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
        $this->app->register(LarapieServiceProvider::class);
    }

    protected function getEnvironmentSetUp($app)
    {
        $app->setBasePath(dirname(__DIR__, 1));
    }

    public function testTesting()
    {
        $this->assertTrue(true);
    }

    public function testFoundationComposerPath()
    {
        $path = Larapie::getFoundation()->getComposerFilePath();
        $this->assertEquals(
            base_path(config('larapie.foundation.path')).'/composer.json',
            $path
        );
    }

    public function testBootstrap()
    {
        $this->artisan('larapie:bootstrap');
        $this->assertTrue(true);
    }

    public function testResolveAlreadyIncludedClass()
    {
        Config::set('larapie.foundation.namespace', 'Larapie\\Core');
        $path = base_path('src/Resolvers/FQNResolver.php');
        $class = FQNResolver::resolve($path);
        $this->assertEquals(FQNResolver::class, $class);
    }

    public function testResolveClass()
    {
        Config::set('larapie.foundation.namespace', 'Larapie\\Core');
        $path = base_path('src/Base/Controller.php');
        $class = FQNResolver::resolve($path);
        $this->assertEquals(Controller::class, $class);
    }

    public function testResources()
    {
        $this->assertEquals(ActionResource::configPath(), '/Actions');
        $this->assertEquals(AttributeResource::configPath(), '/Attributes');
        $this->assertEquals(CommandResource::configPath(), '/Console');
        $this->assertEquals(ConfigResource::configPath(), '/Config');
        $this->assertEquals(ControllerResource::configPath(), '/Http/Controllers');
        $this->assertEquals(EventResource::configPath(), '/Events');
        $this->assertEquals(FactoryResource::configPath(), '/Database/Factories');
        $this->assertEquals(JobResource::configPath(), '/Jobs');
        $this->assertEquals(ListenerResource::configPath(), '/Listeners');
        $this->assertEquals(MiddlewareResource::configPath(), '/Http/Middleware');
        $this->assertEquals(MigrationResource::configPath(), '/Database/Migrations');
        $this->assertEquals(ModelResource::configPath(), '/Models');
        $this->assertEquals(NotificationResource::configPath(), '/Notifications');
        $this->assertEquals(ObserverResource::configPath(), '/Observers');
        $this->assertEquals(PermissionResource::configPath(), '/Permissions');
        $this->assertEquals(PolicyResource::configPath(), '/Policies');
        $this->assertEquals(ProviderResource::configPath(), '/Providers');
        $this->assertEquals(RepositoryResource::configPath(), '/Repositories');
        $this->assertEquals(RequestResource::configPath(), '/Http/Requests');
        $this->assertEquals(RouteResource::configPath(), '/Routes');
        $this->assertEquals(RuleResource::configPath(), '/Rules');
        $this->assertEquals(SeederResource::configPath(), '/Database/Seeders');
        $this->assertEquals(ServiceResource::configPath(), '/Services');
        $this->assertEquals(TestResource::configPath(), '/Tests');
        $this->assertEquals(TransformerResource::configPath(), '/Transformers');
    }
}
