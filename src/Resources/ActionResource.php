<?php

namespace Larapie\Core\Resources;

use Larapie\Core\Abstracts\ClassResource;

class ActionResource extends ClassResource
{
    public static function configPath(): string
    {
        return config('larapie.resources.actions');
    }
}
