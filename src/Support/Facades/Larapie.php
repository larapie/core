<?php

namespace Larapie\Core\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Larapie\Core\Internals\Module[] getModules()
 * @method static \Larapie\Core\Internals\Module getModule(string $module)
 * @method static \Larapie\Core\Internals\Module getPackage(string $module)
 * @method static \Larapie\Core\Internals\Package[] getPackages()
 * @method static string[] getModuleNames()
 * @method static string[] getPackageNames()
 * @method static string getModulePath()
 * @method static string getPackagePath()
 * @method static string getModuleBasePath()
 * @method static string getPackageBasePath()
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
