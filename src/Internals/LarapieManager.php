<?php

namespace Larapie\Core\Internals;

use Larapie\Core\Exceptions\InvalidRouteGroupException;

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
     * Resets the current instances of modules & packages.
     * Useful in some cases when you dynamically generate new modules / packages.
     *
     * @return void
     */
    public function clear()
    {
        self::$modules = null;
        self::$packages = null;
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
     * @return Module
     *
     * @deprecated
     */
    public function getModule(string $name): ?Module
    {
        return $this->getModules()->get($name, null);
    }

    /**
     * @param string $name
     *
     * @return Package
     *
     * @deprecated
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
     * @return string
     *
     * @deprecated
     */
    public function getModulePath(string $module): string
    {
        return self::getModulesBasePath().'/'.$module;
    }

    /**
     * @param string $package
     *
     * @return string
     *
     * @deprecated
     */
    public function getPackagePath(string $package): string
    {
        return self::getPackagesBasePath().'/'.$package;
    }

    /**
     * @return string
     *
     * @deprecated
     */
    public function getModulesBasePath(): string
    {
        return base_path(config('larapie.modules.path'));
    }

    /**
     * @return string
     *
     * @deprecated
     */
    public function getPackagesBasePath(): string
    {
        return base_path(config('larapie.packages.path'));
    }

    public function getGroupUrl(string $groupName): string
    {
        $groups = config('larapie.routing.groups', []);

        if (!array_key_exists($groupName, $groups)) {
            throw new InvalidRouteGroupException($groupName);
        }

        $scheme = parse_url($this->getAppUrl())['scheme'].'://';
        $domain = $groups[$groupName]['domain'] ?? $this->getAppUrl();
        $prefix = $groups[$groupName]['prefix'] ?? null;

        if ($prefix !== null) {
            $domain = $domain.'/'.$prefix;
        }

        return $scheme.$domain;
    }

    public function getAppUrl(): string
    {
        return config('app.url');
    }

    public function getApiUrl(): string
    {
        return $this->getGroupUrl('api');
    }
}
