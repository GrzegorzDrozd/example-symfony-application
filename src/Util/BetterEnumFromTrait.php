<?php

namespace App\Util;

trait BetterEnumFromTrait
{
    public static function fromStringOrEnum(mixed $value): static
    {
        if ($value instanceof static) {
            return $value;
        }

        return static::from($value);
    }
}
