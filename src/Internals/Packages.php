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

class Packages extends Collection
{
    public function __construct()
    {
        parent::__construct([]);
        $this->boot();
    }

    protected function boot()
    {
        foreach ($this->scanForPackages() as $packageName) {
            $this->put(strtolower($packageName), $this->createPackage($packageName));
        }
    }

    protected function find(string $packageName): Package
    {
        return tap($this->get(strtolower($packageName), null), function ($package) {
            if ($package === null) {
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
        return $this->map(function (Package $package) {
            return strtolower($package->getName());
        });
    }

    public function get($name, $default = null)
    {
        return parent::get(strtolower($name), $default);
    }

    /**
     * @return string
     */
    public function getBasePath(): string
    {
        return base_path(config('larapie.packages.path'));
    }

    protected function createPackage($name): Module
    {
        $name = Str::studly($name);

        return new Package($name, $this->getBasePath().'/'.$name);
    }

    protected function scanDirectoryForFolders($path)
    {
        return file_exists($path) && is_dir($path) ? array_diff(scandir($path), ['..', '.']) : [];
    }

    public function getNamespace(): string
    {
        if (Str::startsWith($namespace = config('larapie.packages.namespace'), '\\')) {
            return Str::replaceFirst('\\', '', $namespace);
        }

        return $namespace;
    }
}
