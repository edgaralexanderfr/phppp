<?php

declare(strict_types=1);

namespace PHPTypes\Std;

use PHPTypes\IntType;

class SizeType extends IntType
{
    const int MAX_INT = PHP_INT_MAX;

    /** @disregard */
    public int $value
    {
        get => $this->val;

        /** @disregard */
        set(int $value)
        {
            $this->setValue($value);
        }
    }

    protected function setValue(int $value): void
    {
        if ($value >= self::MAX_INT) {
            $mul = (int) floor(abs($value / self::MAX_INT));
            $value -= self::MAX_INT * $mul;
        } else if ($value < 0) {
            $mul = (int) ceil(abs($value / self::MAX_INT));
            $value += self::MAX_INT * $mul;
        }

        $this->val = $value;
    }
}
