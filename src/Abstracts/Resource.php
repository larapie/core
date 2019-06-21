<?php

namespace Larapie\Core\Abstracts;

use Larapie\Core\Internals\Module;

class Resource
{
    /**
     * @var string
     */
    protected $path;

    /**
     * @var Module
     */
    protected $module;

    /**
     * File constructor.
     *
     * @param string $path
     * @param Module $module
     */
    public function __construct(string $path, Module $module)
    {
        $this->path = $path;
        $this->module = $module;
        $this->boot();
    }

    protected function boot()
    {
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return basename($this->path, '.'.$this->getExtension());
    }

    public function getExtension(): string
    {
        return pathinfo($this->path)['extension'] ?? '';
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    public function getDirectory()
    {
        return dirname($this->path);
    }

    public function getFileName(): string
    {
        return basename($this->path);
    }

    /**
     * @return Module
     */
    public function getModule(): Module
    {
        return $this->module;
    }

    public function hasPhpExtension()
    {
        return $this->getExtension() == 'php';
    }

    public function isValid()
    {
        return $this->hasPhpExtension();
    }

    public function toArray()
    {
        return [
            'path'      => $this->getPath(),
            'directory' => $this->getDirectory(),
            'name'      => $this->getName(),
            'filename'  => $this->getFileName(),
            'module'    => $this->getModule()->getName(),
        ];
    }
}
