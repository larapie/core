<?php

namespace Larapie\Core\Abstracts;

use Illuminate\Support\Str;
use Larapie\Core\Resolvers\FQNResolver;

abstract class ClassResource extends Resource
{
    protected $fqn;

    protected function boot()
    {
        $this->resolveClassAndNamespace();
    }

    protected function resolveClassAndNamespace()
    {
        $this->fqn = FQNResolver::resolve($this->path);
    }

    /**
     * @return mixed
     */
    public function getClassName()
    {
        return get_short_class_name($this->fqn);
    }

    /**
     * @return mixed
     */
    public function getNamespace()
    {
        return Str::replaceLast('\\', '', str_replace($this->getClassName(), '', $this->fqn));
    }

    public function isValid()
    {
        return parent::isValid() && $this->getClassName() !== null && $this->getClassName() !== '';
    }

    public function getFQN()
    {
        return $this->fqn;
    }

    public function toArray()
    {
        $resource = parent::toArray();
        $class = [
            'class'     => $this->getClassName(),
            'namespace' => $this->getNamespace(),
            'fqn'       => $this->getFQN(),
        ];

        return array_merge($resource, $class);
    }
}
