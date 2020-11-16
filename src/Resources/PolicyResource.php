<?php

namespace Larapie\Core\Resources;

use Larapie\Core\Abstracts\ClassResource;

class PolicyResource extends ClassResource
{
    public static function configPath(): string
    {
        return config('larapie.resources.policies');
    }
}
