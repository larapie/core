<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 09.03.19
 * Time: 21:51.
 */

namespace Larapie\Core\Internals;

class Foundation
{
    /**
     * @var string
     */
    protected $path;

    /**
     * LarapiModule constructor.
     *
     * @param $name
     */
    public function __construct()
    {
        $this->path = base_path(config('larapie.foundation.path'));
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    public function getComposerFilePath()
    {
        return $this->getPath().'/composer.json';
    }

    public function getNamespace(): string
    {
        return config('larapie.foundation.namespace');
    }
}
