<?php

declare(strict_types=1);

namespace PHPTypes\Primitive;

use PHPTypes\IntType;
use TypeError;

class CharType extends IntType
{
    private string $chr = '';

    #[Override]
    public function __construct(string|int $value)
    {
        $this->setValue($value);
    }

    /** @disregard */
    public int $value
    {
        get => $this->val;

        /** @disregard */
        set(string|int $value)
        {
            $this->setValue($value);
        }
    }

    /** @disregard */
    #[Override]
    public function __toString(): string
    {
        return $this->chr;
    }

    /** @disregard */
    protected function setValue(string|int $value): void
    {
        if (is_string($value)) {
            $len = strlen($value);

            if ($len == 0) {
                throw new TypeError('Empty character constant, called');
            }

            if ($len != 1) {
                trigger_error('Character constant too long for its type', E_USER_WARNING);
            }

            $value = (int) ord($value[$len - 1]);
        }

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
        $this->chr = chr($value);
    }
}
