<?php

namespace Larapie\Core\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Larapie\Core\Internals\Module[]  getModules()
 * @method static \Larapie\Core\Internals\Package[]  getPackages()
 *
 * @see \Larapie\Core\Internals\LarapieManager
 */
class Larapie extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'larapie';
    }
}
