<?php

namespace Larapie\Core\Support\Facades;

use Illuminate\Support\Facades\Facade;
use Larapie\Core\Internals\Foundation;
use Larapie\Core\Internals\Modules;
use Larapie\Core\Internals\Packages;

/**
 *
 * @see \Larapie\Core\Services\FactoryService
 */
class ModelFactory extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'modelfactory';
    }
}
