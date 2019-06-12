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

        foreach (self::getModuleNames() as $moduleName) {
            $modules[] = $this->getModule($moduleName);
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
            $packages[] = $this->getPackage($package);
        }

        return $packages;
    }

    /**
     * @param string $name
     * @return Module
     */
    public function getModule(string $name): Module
    {
        $name = Str::studly($name);
        return new Module($name, $this->getModulePath($name));
    }

    /**
     * @param string $name
     * @return Module
     */
    public function getPackage(string $name): Module
    {
        $name = Str::studly($name);
        return new Package($name, $this->getPackagePath($name));
    }

    protected function getFilesFromDirectory($path){
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
     * @return string
     */
    public function getModulePath(string $module): string
    {
        return self::getModulesBasePath() . '/' . $module;
    }

    /**
     * @param string $package
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