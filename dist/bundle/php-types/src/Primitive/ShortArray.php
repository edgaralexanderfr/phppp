<?php

declare(strict_types=1);

namespace PHPTypes\Primitive;

use PHPTypes\ArrayObject;

use function PHPTypes\Primitive\short;

class ShortArray extends ArrayObject
{
    protected array $object = [
        short::class => short::class,
    ];

    public function __construct(short|int ...$values)
    {
        parent::__construct(
            array_map(fn($value) => ($value instanceof short) ? $value : short($value), $values)
        );
    }
}
