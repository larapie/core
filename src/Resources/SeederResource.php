<?php

namespace Larapie\Core\Resources;

use Larapie\Core\Abstracts\ClassResource;

class SeederResource extends ClassResource
{
    public static function configPath(): string
    {
        return config('larapie.resources.seeders');
    }
}
