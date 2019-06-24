<?php

namespace Larapie\Core\Abstracts;

use Illuminate\Support\Str;
use Larapie\Core\Resolvers\ClassDataResolver;

abstract class ClassResource extends Resource
{
    protected $class;

    protected $namespace;

    protected function boot()
    {
        $this->resolveClassAndNamespace();
    }

    protected function resolveClassAndNamespace()
    {
        try {
            $resolver = new ClassDataResolver($this->path);
            $this->class = $resolver->getClass();
            $this->namespace = $resolver->getNamespace();
        } catch (\Throwable $e) {
        }
    }

    /**
     * @return mixed
     */
    public function getClassName()
    {
        return $this->class;
    }

    /**
     * @return mixed
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    public function isValid()
    {
        return parent::isValid() && $this->getClassName() !== null && $this->getClassName() !== '';
    }

    public function getFQN()
    {
        return Str::replaceFirst('\\', '', $this->getNamespace() . '\\' . $this->getClassName());
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
