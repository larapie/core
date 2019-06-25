<?php

namespace Larapie\Core\Resolvers;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;

/**
 * Class FQNResolver
 *
 * Resolve the fully qualified namespace from a filepath.
 * @todo add a 3th failswitch to get the namespace by parsing the file itself.
 */
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
        $class = null;
        $classes = get_declared_classes();
        $alreadyLoaded = include_once $path;
        if (is_bool($alreadyLoaded) && $alreadyLoaded) {
            $pathinfo = pathinfo($path);

            //loop in reverse because it's more likely to find the correct class at the end of the stack.
            for ($i = count($classes) - 1; $i >= 0; $i--) {
                if (Str::endsWith($classes[$i], $pathinfo['filename'])) {
                    $class = $classes[$i];
                    break;
                }
            }
        } else {
            $diff = array_diff(get_declared_classes(), $classes);
            $class = end($diff);
        }

        if ($class === null) {
            //TODO parse class with tokenizer and extract namespace
            throw new \RuntimeException("Could not extract fully qualified namespace from filepath. Please make an issue if you encounter this issue at https://github.com/larapie/core");
        }

        return tap($class, function ($class) use ($path) {
            self::$resolved[$path] = $class;
        });
    }
}
