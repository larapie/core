<?php

if (!function_exists('get_short_class_name')) {
    function get_short_class_name($class)
    {
        if (!is_string($class)) {
            $class = get_class($class);
        }

        return substr(strrchr($class, '\\'), 1);
    }
}

if (!function_exists('class_implements_interface')) {
    function class_implements_interface($class, $interface)
    {
        return in_array($interface, class_implements($class));
    }
}

if (!function_exists('class_uses_trait')) {
    function class_uses_trait($class, string $trait)
    {
        if (!is_string($class)) {
            $class = get_class($class);
        }

        $traits = array_flip(class_uses_recursive($class));

        return isset($traits[$trait]);
    }
}

if (!function_exists('array_is_subset_of')) {
    function array_is_subset_of(array $subset, array $array, bool $strict = false)
    {
        $arrayAssociative = is_associative_array($array);
        $subsetAssociative = is_associative_array($subset);

        if ($subsetAssociative && $arrayAssociative) {
            $patched = \array_replace_recursive($array, $subset);

            if ($strict) {
                $result = $array === $patched;
            } else {
                $result = $array == $patched;
            }

            return $result;
        } elseif (($subsetAssociative && !$arrayAssociative) ||
            (!$subsetAssociative && $arrayAssociative)) {
            return false;
        }

        $result = array_intersect($subset, $array);

        if ($strict) {
            return $result === $subset;
        }

        return $result == $subset;
    }
}

if (!function_exists('is_associative_array')) {
    function is_associative_array(array $arr)
    {
        if ([] === $arr) {
            return false;
        }

        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}

if (!function_exists('get_class_property')) {
    function get_class_property($class, string $property)
    {
        if (!is_string($class)) {
            $class = get_class($class);
        }

        try {
            $reflectionClass = new \ReflectionClass($class);
            $property = $reflectionClass->getProperty($property);
            $property->setAccessible(true);

            return $property->getValue($reflectionClass->newInstanceWithoutConstructor());
        } catch (ReflectionException $e) {
            throw $e;
        }
    }
}

if (!function_exists('instance_without_constructor')) {
    function instance_without_constructor($class)
    {
        if (!is_string($class)) {
            $class = get_class($class);
        }

        try {
            $reflectionClass = new \ReflectionClass($class);
        } catch (ReflectionException $e) {
            return;
        }
        if ($reflectionClass->isAbstract()) {
            return;
        }

        return $reflectionClass->newInstanceWithoutConstructor();
    }
}

if (!function_exists('call_class_function')) {
    function call_class_function($class, string $methodName)
    {
        return instance_without_constructor($class)->$methodName();
    }
}

if (!function_exists('get_class_constants')) {
    function get_class_constants($class)
    {
        if (!is_string($class)) {
            $class = get_class($class);
        }

        try {
            $reflectionClass = new \ReflectionClass($class);
        } catch (ReflectionException $e) {
            return;
        }

        return $reflectionClass->getConstants();
    }
}
