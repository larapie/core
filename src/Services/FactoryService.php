<?php

namespace Larapie\Core\Services;

use Illuminate\Database\Eloquent\Factories\Factory;

class FactoryService
{
    protected array $models = [];

    public function add(string $model, string $factory): void
    {
        if (array_key_exists($model, $this->models)) {
            throw new \RuntimeException("Cannot associate factory $factory  with $model. Can only link one factory per model");
        }
        $this->models[$model] = $factory;
    }

    /**
     * @param string $model
     *
     * @return string | Factory
     */
    public function get(string $model): string
    {
        if (array_key_exists($model, $this->models)) {
            return $this->models[$model];
        }

        throw new \RuntimeException("No factory found for model $model");
    }
}
