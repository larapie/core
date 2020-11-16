<?php

namespace Tests;

use Illuminate\Support\Facades\Config;
use Larapie\Core\Base\Controller;
use Larapie\Core\LarapieServiceProvider;
use Larapie\Core\Resolvers\FQNResolver;
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
}
