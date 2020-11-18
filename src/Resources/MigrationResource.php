<?php

namespace Larapie\Core\Resources;

use Larapie\Core\Abstracts\Resource;

class MigrationResource extends Resource
{
    public static function configPath(): string
    {
        return config('larapie.resources.migrations');
    }
}
