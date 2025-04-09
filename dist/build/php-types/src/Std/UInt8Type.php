<?php

declare(strict_types=1);

namespace PHPTypes\Std;

use PHPTypes\IntType;

class UInt8Type extends IntType
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
        if ($value >= 256) {
            $mul = (int) floor(abs($value / 256));
            $value -= 256 * $mul;
        } else if ($value < 0) {
            $mul = (int) ceil(abs($value / 256));
            $value += 256 * $mul;
        }

        $this->val = $value;
    }
}
