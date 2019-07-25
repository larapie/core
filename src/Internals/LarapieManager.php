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

    public function getAppUrl(): string
    {
        return config('app.url');
    }

    public function getApiUrl(): string
    {
        $scheme = null;
        $apiUri = config('larapie.api_url');

        if ($apiUri !== null) {
            $scheme = parse_url($apiUri)['scheme'].'://';
        } else {
            $scheme = parse_url($appUri = $this->getAppUrl())['scheme'].'://' ?? 'http://';
        }

        if (($sub = config('larapie.api_subdomain')) !== null) {
            if ($apiUri === null) {
                return $scheme.$sub.'.'.parse_url($appUri, PHP_URL_HOST);
            }
            $this->getAppUrl();

            return $scheme.$sub.'.'.$apiUri;
        }

        if ($apiUri === null) {
            return $this->getAppUrl().'/api';
        }

        return $scheme.$apiUri;
    }
}
