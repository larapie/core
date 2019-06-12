<?php

namespace Larapie\Core\Resolvers;

use Illuminate\Contracts\Filesystem\FileNotFoundException;

class ClassDataResolver
{
    protected $class;

    protected $namespace;

    public function __construct(string $path)
    {
        if (!file_exists($path)) {
            throw new FileNotFoundException();
        }

        try {
            @$this->resolve($path);
        } catch (\Throwable $e) {
        }
    }

    //TODO proper resolving without tokenizer (could throw warnings atm)
    protected function resolve($path)
    {
        $fp = fopen($path, 'r');
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
        $this->class = $class;
        $this->namespace = $namespace;
    }

    /**
     * @return mixed
     */
    public function getClass()
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
}
