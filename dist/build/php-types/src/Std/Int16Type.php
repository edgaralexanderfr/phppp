<?php

declare(strict_types=1);

namespace PHPTypes\Std;

use PHPTypes\IntType;

class Int16Type extends IntType
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
        if ($value >= 32768) {
            $value += 32768;
            $mul = (int) floor(abs($value / 65536));
            $value -= 65536 * $mul;
            $value -= 32768;
        } else if ($value < -32768) {
            $value += 32768;
            $mul = (int) ceil(abs($value / 65536));
            $value += 65536 * $mul;
            $value -= 32768;
        }

        $this->val = $value;
    }
}
