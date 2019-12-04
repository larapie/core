<?php

namespace Larapie\Core\Support\Facades;

use Illuminate\Support\Facades\Facade;
use Larapie\Core\Internals\Foundation;
use Larapie\Core\Internals\Modules;
use Larapie\Core\Internals\Packages;

/**
 * @method static \Larapie\Core\Internals\Module[] | Modules getModules()
 * @method static \Larapie\Core\Internals\Package[] | Packages getPackages()
 * @method static string[] getModuleNames()
 * @method static string[] getPackageNames()
 * @method static string getModulePath()
 * @method static Foundation getFoundation()
 * @method static string getPackagePath()
 * @method static string getModuleBasePath()
 * @method static string getPackageBasePath()
 * @method static string getAppUrl()
 * @method static string getApiUrl()
 * @method static string getGroupUrl(string $groupName)
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
