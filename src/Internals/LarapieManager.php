<?php

namespace Larapie\Core\Internals;

use Illuminate\Support\Str;

class LarapieManager
{
    /**
     * @return Module[]
     */
    public function getModules()
    {
        $modules = [];

        foreach (self::getModuleNames() as $module) {
            $modules[$module] = $this->createModule($module);
        }

        return $modules;
    }

    /**
     * @return Package[]
     */
    public function getPackages()
    {
        $packages = [];

        foreach (self::getPackageNames() as $package) {
            $packages[$package] = $this->createPackage($package);
        }

        return $packages;
    }

    /**
     * @param string $name
     *
     * @return Module
     */
    public function getModule(string $name): ?Module
    {
        $name = Str::studly($name);
        $modules = $this->getModules();

        if (array_key_exists($name, $modules))
            return $modules[$name];

        return null;
    }

    protected function createModule($name): Module
    {
        $name = Str::studly($name);
        return new Module($name, $this->getModulePath($name));
    }

    protected function createPackage($name): Package
    {
        $name = Str::studly($name);
        return new Package($name, $this->getPackagePath($name));
    }

    /**
     * @param string $name
     *
     * @return Package
     */
    public function getPackage(string $name): ?Package
    {
        $name = Str::studly($name);
        $packages = $this->getPackages();

        if (array_key_exists($name, $packages))
            return $packages[$name];

        return null;
    }

    protected function getFilesFromDirectory($path)
    {
        return array_diff(scandir($path), ['..', '.']);
    }

    /**
     * @return string[]
     */
    public function getModuleNames(): array
    {
        return $this->getFilesFromDirectory($this->getModulesBasePath());
    }

    /**
     * @return string[]
     */
    public function getPackageNames(): array
    {
        return $this->getFilesFromDirectory($this->getPackagesBasePath());
    }

    /**
     * @param string $module
     *
     * @return string
     */
    public function getModulePath(string $module): string
    {
        return self::getModulesBasePath() . '/' . $module;
    }

    /**
     * @param string $package
     *
     * @return string
     */
    public function getPackagePath(string $package): string
    {
        return self::getPackagesBasePath() . '/' . $package;
    }

    /**
     * @return string
     */
    public function getModulesBasePath(): string
    {
        return base_path(config('larapie.modules.path'));
    }

    /**
     * @return string
     */
    public function getPackagesBasePath(): string
    {
        return base_path(config('larapie.packages.path'));
    }
}
