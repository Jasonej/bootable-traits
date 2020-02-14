<?php

namespace Jasonej\BootableTraits;

trait BootsTraits
{
    public static function bootTraits(?object $instance = null): void
    {
        foreach (Traits::traitBootMethods(static::class) as $bootMethod) {
            $methodName = $bootMethod->name;
            if ($bootMethod->isStatic()) {
                static::$methodName();
            } elseif ($instance !== null) {
                $instance->$methodName();
            }
        }
    }
}