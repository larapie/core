<?php


namespace Larapie\Core\Services;


use Illuminate\Database\Eloquent\Factories\Factory;

class FactoryService
{
    protected static $models = [];

    public static function add(string $model, string $factory): void
    {
        if (array_key_exists($model, static::$models))
            throw new \RuntimeException("Cannot associate factory $factory  with $model. Can only link one factory per model");

        $models[$model] = $factory;
    }


    /**
     * @param string $model
     * @return string | Factory
     */
    public static function get(string $model): string
    {
        if (array_key_exists($model, static::$models))
            return static::$models[$model];

        throw new \RuntimeException("No factory found for model $model");
    }
}
