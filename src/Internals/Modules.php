<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 09.03.19
 * Time: 21:51.
 */

namespace Larapie\Core\Internals;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Modules extends Collection
{
    public function __construct()
    {
        parent::__construct([]);
        $this->boot();
    }

    protected function boot()
    {
        foreach ($this->scanForPackages() as $moduleName) {
            $this->put(strtolower($moduleName), $this->createPackage($moduleName));
        }
    }

    public function get($name, $default = null)
    {
        return parent::get(strtolower($name), $default);
    }

    public function find(string $moduleName): Module
    {
        return tap($this->get(strtolower($moduleName), null), function ($module) {
            if ($module === null) {
                throw new \RuntimeException('module does not exist');
            }
        });
    }

    protected function scanForPackages()
    {
        return $this->scanDirectoryForFolders($this->getBasePath());
    }

    /**
     * @return string[] | Collection
     */
    public function getNames()
    {
        return $this->map(function (Module $module) {
            return strtolower($module->getName());
        });
    }

    /**
     * @return string
     */
    public function getBasePath(): string
    {
        return base_path(config('larapie.modules.path'));
    }

    protected function createPackage($name): Module
    {
        $name = Str::studly($name);

        return new Module($name, $this->getBasePath() . '/' . $name);
    }

    protected function scanDirectoryForFolders($path)
    {
        return file_exists($path) && is_dir($path) ? array_diff(scandir($path), ['..', '.']) : [];
    }

    public function getNamespace(): string
    {
        if (Str::startsWith($namespace = config('larapie.modules.namespace'), '\\')) {
            return Str::replaceFirst('\\', '', $namespace);
        }

        return $namespace;
    }
}
