<?php

declare(strict_types=1);

namespace PHPTypes\Primitive;

use PHPTypes\ArrayObject;

use function PHPTypes\Primitive\uchar;

class UCharArray extends ArrayObject implements \Stringable
{
    protected array $object = [
        UCharType::class => UCharType::class,
        uchar::class => uchar::class,
    ];

    public function __construct(UCharType|uchar|string|int ...$values)
    {
        parent::__construct(
            array_map(fn($value) => ($value instanceof UCharType || $value instanceof uchar) ? $value : uchar($value), $values)
        );
    }

    public function __toString(): string
    {
        return implode('', (array) $this) . "\0";
    }
}
