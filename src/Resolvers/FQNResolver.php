<?php

namespace Larapie\Core\Resolvers;

use Illuminate\Contracts\Filesystem\FileNotFoundException;

class FQNResolver
{
    protected static $resolved = [];

    public static function resolve(string $path)
    {
        if (array_key_exists($path, self::$resolved))
            return self::$resolved[$path];

        if (!file_exists($path)) {
            throw new FileNotFoundException();
        }

        $classes = get_declared_classes();
        include $path;
        $diff = array_diff(get_declared_classes(), $classes);

        return tap(end($diff), function ($class) use ($path) {
            self::$resolved[$path] = $class;
        });
    }
}
