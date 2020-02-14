<?php

namespace Jasonej\BootableTraits;

use ReflectionClass;
use ReflectionMethod;

class Traits
{
    public static function traitBootMethods(string $class)
    {
        $traits = static::usedByClass($class, true);

        return array_filter(array_map([static::class, 'getBootMethod'], $traits));
    }

    public static function usedByClass(string $class, bool $recursive = false): array
    {
        $traits = class_uses($class);

        if ($recursive) {
            $parent = get_parent_class($class);

            if ($parent) {
                $traits = array_merge($traits, static::usedByClass($parent, $recursive));
            }

            foreach ($traits as $trait) {
                $traits = array_merge($traits, static::usedByClass($trait, $recursive));
            }
        }

        return array_unique($traits);
    }

    protected static function getBootMethod(string $trait): ?ReflectionMethod
    {
        $reflectionClass = new ReflectionClass($trait);
        $traitName = $reflectionClass->getShortName();
        $bootMethodName = "boot{$traitName}";

        return $reflectionClass->hasMethod($bootMethodName)
            ? $reflectionClass->getMethod($bootMethodName)
            : null;
    }
}