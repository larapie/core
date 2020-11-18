<?php

namespace Larapie\Core\Support\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Larapie\Core\Services\FactoryService
 * @method static void add(string $model, string $factory)
 * @method static string get(string $model)
 */
class ModelFactory extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'modelfactory';
    }
}
