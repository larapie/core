<?php

namespace Larapie\Core\Traits;

use Illuminate\Contracts\Console\Kernel;

trait CreatesApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $bootstrapFile = dirname(__FILE__, 6).'/bootstrap/app.php';
        $app = require $bootstrapFile;

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }
}
