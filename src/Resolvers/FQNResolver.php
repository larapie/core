<?php

namespace Larapie\Core\Resolvers;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Str;
use Larapie\Core\Support\Facades\Larapie;

/**
 * Class FQNResolver.
 *
 * Resolve the fully qualified namespace from a filepath.
 * Resolving is cached to speed up performance.
 */
class FQNResolver
{
    protected static $resolved = [];
    protected static $classes;

    public static function resolve(string $filePath)
    {
        if (array_key_exists($filePath, self::$resolved)) {
            return self::$resolved[$filePath];
        }

        if (!file_exists($filePath)) {
            throw new FileNotFoundException();
        }
        $class = null;
        $classes = collect(get_declared_classes());
        $path = $filePath;
        $alreadyLoaded = include_once $filePath;

        //SECOND DETECTION METHOD. HAPPENS WHEN CLASS IS ALREADY INCLUDED
        if (is_bool($alreadyLoaded) && $alreadyLoaded) {
            $class = self::resolveFQNFromParsing($filePath);
        } //PREFERRED & MOST RELIABLE DETECTION METHOD
        else {
            $class = collect(get_declared_classes())
                ->diff($classes)
                ->last();
        }

        //THIRD DETECTION METHOD. HAPPENS WHEN CLASS NAME IS DIFFERENT FROM FILENAME. (UNLIKELY)
        if ($class === null) {
            $class = self::resolveFQNFromParsing($path);
        }

        //This shouldn't happen!
        if ($class === null) {
            throw new \RuntimeException('Could not extract fully qualified namespace from filepath. Please make an issue if you encounter this issue at https://github.com/larapie/core');
        }

        if (Str::startsWith($class, '\\')) {
            $class = Str::replaceFirst('\\', '', $class);
        }

        return tap($class, function ($class) use ($filePath) {
            self::$resolved[$filePath] = $class;
        });
    }

    protected static function getClasses()
    {
        if (!isset(self::$classes)) {
            self::$classes = collect(get_declared_classes())
                ->filter(function ($class) {
                    return Str::startsWith($class, Larapie::getFoundation()->getNamespace()) ||
                        Str::startsWith($class, Larapie::getModules()->getNamespace()) ||
                        Str::startsWith($class, Larapie::getPackages()->getNamespace());
                });
        }

        return self::$classes;
    }

    protected function resolveFromDeclaredClasses(string $filePath): ?string
    {
        $filePath = strtolower($filePath);
        $pathinfo = pathinfo($filePath);
        $class = self::getClasses()->each(function (string $currentClass) use ($pathinfo, $filePath) {
            if (Str::endsWith(strtolower($currentClass), $fileName = $pathinfo['filename'])) {
                $matches = 0;
                $class = null;
                $currentMatches = 0;
                collect($exploded = explode('\\', strtolower($currentClass)))
                    ->slice(0, -1)
                    ->reverse()
                    ->each(function ($partOfNamespace) use ($filePath, $fileName, &$matches, $currentClass, &$class, &$currentMatches) {
                        $explodedpath = explode('/', $filePath);
                        if (!array_key_exists($key = (count($explodedpath) - 2 - $currentMatches), $explodedpath)) {
                            $currentMatches = 0;

                            return false;
                        }
                        $currentPathComparison = $explodedpath[count($explodedpath) - 2 - $currentMatches];
                        if (Str::is($currentPathComparison, $partOfNamespace)) {
                            $currentMatches++;
                            if ($currentMatches > $matches) {
                                $class = $currentClass;
                                $matches = $currentMatches;

                                return;
                            }
                        } else {
                            $currentMatches = 0;

                            return false;
                        }
                    });
            }
        });
    }

    protected static function resolveFQNFromParsing(string $file): string
    {
        $fp = fopen($file, 'r');
        $class = $namespace = $buffer = '';
        $i = 0;
        while (!$class) {
            if (feof($fp)) {
                break;
            }

            $buffer .= fread($fp, 512);
            $tokens = token_get_all($buffer);

            if (strpos($buffer, '{') === false) {
                continue;
            }

            for (; $i < count($tokens); $i++) {
                if ($tokens[$i][0] === T_NAMESPACE) {
                    for ($j = $i + 1; $j < count($tokens); $j++) {
                        if ($tokens[$j][0] === T_STRING) {
                            $namespace .= '\\'.$tokens[$j][1];
                        } elseif ($tokens[$j] === '{' || $tokens[$j] === ';') {
                            break;
                        }
                    }
                }

                if ($tokens[$i][0] === T_CLASS) {
                    for ($j = $i + 1; $j < count($tokens); $j++) {
                        if ($tokens[$j] === '{') {
                            $class = $tokens[$i + 2][1];
                        }
                    }
                }
            }
        }

        return $namespace.'\\'.$class;
    }
}