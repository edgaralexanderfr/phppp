<?php

declare(strict_types=1);

namespace PHPTypes\Primitive;

use PHPTypes\ArrayObject;

use function PHPTypes\Primitive\byte;

class ByteArray extends ArrayObject
{
    protected array $object = [
        byte::class => byte::class,
    ];

    public function __construct(byte|int ...$values)
    {
        parent::__construct(
            array_map(fn($value) => ($value instanceof byte) ? $value : byte($value), $values)
        );
    }
}
