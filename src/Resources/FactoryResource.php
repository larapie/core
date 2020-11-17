<?php

namespace Larapie\Core\Resources;

use Illuminate\Database\Eloquent\Factories\Factory;
use Larapie\Core\Abstracts\ClassResource;

class FactoryResource extends ClassResource
{
    protected string $model;

    protected function boot()
    {
        parent::boot();
        $this->extractModel();
    }

    protected function extractModel(): string
    {
        $model = null;

        if (property_exists($this->getFQN(), 'model'))
            $model = instance_without_constructor($this->getFQN())->model;

        if (is_string($model) && class_exists($model))
            throw new \RuntimeException("Factory needs to have a valid model declared as property");

        return $model;
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        return $this->model;
    }

    protected function baseClass(): ?string
    {
        return Factory::class;
    }

    public function toArray()
    {
        return array_merge(parent::toArray(), ['model' => $this->getModel()]);
    }

    public static function configPath(): string
    {
        return config('larapie.resources.factories');
    }
}
