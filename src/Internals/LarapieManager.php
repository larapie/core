<?php

namespace Larapie\Core\Internals;

class LarapieManager
{
    private static $modules;
    private static $packages;

    /**
     * @return Module[] | Modules
     */
    public function getModules()
    {
        if (!isset(self::$modules)) {
            self::$modules = new Modules();
        }

        return self::$modules;
    }

    /**
     * @return Package[] | Packages
     */
    public function getPackages()
    {
        if (!isset(self::$packages)) {
            self::$packages = new Packages();
        }

        return self::$packages;
    }

    /**
     * @param string $name
     *
     * @deprecated
     *
     * @return Module
     */
    public function getModule(string $name): ?Module
    {
        return $this->getModules()->get($name, null);
    }

    /**
     * @param string $name
     *
     * @deprecated
     *
     * @return Package
     */
    public function getPackage(string $name): ?Package
    {
        return $this->getPackages()->get($name, null);
    }

    public function getFoundation(): Foundation
    {
        return new Foundation();
    }

    /**
     * @return string[]
     */
    public function getModuleNames(): array
    {
        return $this->getModules()->getNames();
    }

    /**
     * @return string[]
     *
     * @deprecated
     */
    public function getPackageNames(): array
    {
        return $this->getPackages()->getNames();
    }

    /**
     * @param string $module
     *
     * @deprecated
     *
     * @return string
     */
    public function getModulePath(string $module): string
    {
        return self::getModulesBasePath().'/'.$module;
    }

    /**
     * @param string $package
     *
     * @deprecated
     *
     * @return string
     */
    public function getPackagePath(string $package): string
    {
        return self::getPackagesBasePath().'/'.$package;
    }

    /**
     * @deprecated
     *
     * @return string
     */
    public function getModulesBasePath(): string
    {
        return base_path(config('larapie.modules.path'));
    }

    /**
     * @deprecated
     *
     * @return string
     */
    public function getPackagesBasePath(): string
    {
        return base_path(config('larapie.packages.path'));
    }

    public function getAppUrl(): string
    {
        return config('app.url');
    }

    public function getApiUrl(): string
    {
        return config('larapie.api_url') === null ? config('app.url').'/api' : config('larapie.api_url');
    }
}
