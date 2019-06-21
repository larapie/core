<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 09.03.19
 * Time: 21:51.
 */

namespace Larapie\Core\Internals;

class Package extends Module
{
    public function getNamespace(): string
    {
        return config('larapie.packages.namespace').'\\'.$this->getName();
    }
}
