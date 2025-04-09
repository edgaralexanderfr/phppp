<?php

declare(strict_types=1);

namespace PHPTypes\Std;

use PHPTypes\ArrayObject;

class UInt16Array extends ArrayObject
{
    protected array $object = [
        UInt16Type::class => UInt16Type::class,
        uint16_t::class => uint16_t::class,
    ];

    public function __construct(UInt16Type|uint16_t|int ...$values)
    {
        parent::__construct(
            array_map(fn($value) => ($value instanceof UInt16Type || $value instanceof uint16_t) ? $value : uint16_t($value), $values)
        );
    }
}
