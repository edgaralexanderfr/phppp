<?php

declare(strict_types=1);

namespace PHPTypes\Std;

use PHPTypes\ArrayObject;

class Int8Array extends ArrayObject
{
    protected array $object = [
        Int8Type::class => Int8Type::class,
        int8_t::class => int8_t::class,
    ];

    public function __construct(Int8Type|int8_t|int ...$values)
    {
        parent::__construct(
            array_map(fn($value) => ($value instanceof Int8Type || $value instanceof int8_t) ? $value : int8_t($value), $values)
        );
    }
}
