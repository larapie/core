<?php

namespace Tests;

use Larapie\Core\LarapieServiceProvider;
use Larapie\Core\Support\Facades\Larapie;
use Orchestra\Testbench\TestCase;

class CoreTests extends TestCase
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

    public function testFoundationComposerPath(){
        $path = Larapie::getFoundation()->getComposerFilePath();
        $this->assertEquals(
            base_path(config('larapie.foundation.path')).'/composer.json',
            $path
        );
    }

    public function testBootstrap(){
        $this->artisan('larapie:bootstrap');
    }
}
