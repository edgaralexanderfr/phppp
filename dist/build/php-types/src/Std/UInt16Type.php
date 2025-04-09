<?php

declare(strict_types=1);

namespace PHPTypes\Std;

use PHPTypes\IntType;

class UInt16Type extends IntType
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
        if ($value >= 65536) {
            $mul = (int) floor(abs($value / 65536));
            $value -= 65536 * $mul;
        } else if ($value < 0) {
            $mul = (int) ceil(abs($value / 65536));
            $value += 65536 * $mul;
        }

        $this->val = $value;
    }
}
