<?php

declare(strict_types=1);

namespace PHPTypes\Std;

use PHPTypes\IntType;

class Int8Type extends IntType
{
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
        if ($value >= 128) {
            $value += 128;
            $mul = (int) floor(abs($value / 256));
            $value -= 256 * $mul;
            $value -= 128;
        } else if ($value < -128) {
            $value += 128;
            $mul = (int) ceil(abs($value / 256));
            $value += 256 * $mul;
            $value -= 128;
        }

        $this->val = $value;
    }
}
