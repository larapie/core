<?php

namespace Larapie\Core\Collections;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Larapie\Core\Abstracts\ClassResource;
use Larapie\Core\Abstracts\Resource;
use Larapie\Core\Internals\Module;

class ResourceCollection extends Collection
{
    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var Module
     */
    protected $module;

    protected function __construct($items = [])
    {
        parent::__construct($items);
    }

    /**
     * @param string $type
     */
    protected function setType(string $type): void
    {
        $this->type = $type;
    }

    /**
     * @param string $path
     */
    protected function setPath(string $path): void
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return Module
     */
    public function getModule(): Module
    {
        return $this->module;
    }

    /**
     * @param Module $module
     */
    public function setModule(Module $module): void
    {
        $this->module = $module;
    }

    public static function fromPath(string $path, Module $module, string $resourceType)
    {
        $collection = new static();
        $collection->setPath($path);
        $collection->setNamespace(str_replace('/', '\\', $module->getNamespace().str_replace($module->getPath(), '', $path)));
        $collection->setType($resourceType);
        $collection->setModule($module);

        try {
            $files = array_diff(scandir($path), ['..', '.']);
        } catch (\ErrorException $e) {
            $files = [];
        }

        foreach ($files as $file) {
            if (pathinfo($path.'/'.$file)['extension'] === 'php') {
                $resource = new $resourceType($path.'/'.$file, $module);
                if ($resource->isValid()) {
                    $collection->add($resource);
                }
            }
        }

        return $collection;
    }

    public function getClassNames()
    {
        $classes = [];
        foreach ($this as $resource) {
            if ($resource instanceof ClassResource) {
                $classes[] = $resource->getClassName();
            }
        }

        return $classes;
    }

    public function getFQNClasses()
    {
        $fqns = [];
        foreach ($this as $resource) {
            if ($resource instanceof ClassResource) {
                $fqns[] = $resource->getFQN();
            }
        }

        return $fqns;
    }

    public function getFileNames()
    {
        $files = [];
        foreach ($this as $resource) {
            $files[] = $resource->getFileName();
        }

        return $files;
    }

    public function getNamespace()
    {
        return $this->namespace;
    }

    public function getFilteredNamespace()
    {
        return Str::startsWith('\\', $this->namespace) ? Str::replaceFirst('\\', '', $this->namespace) : $this->namespace;
    }

    protected function setNamespace(string $namespace)
    {
        $this->namespace = $namespace;
    }

    public function toArray()
    {
        $this->items = $this->transform(function (Resource $resource) {
            return $resource->toArray();
        })->items;

        return parent::toArray();
    }
}
