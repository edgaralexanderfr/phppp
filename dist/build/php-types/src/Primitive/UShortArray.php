<?php

declare(strict_types=1);

namespace PHPTypes\Primitive;

use PHPTypes\ArrayObject;

use function PHPTypes\Primitive\ushort;

class UShortArray extends ArrayObject
{
    protected array $object = [
        ushort::class => ushort::class,
    ];

    public function __construct(ushort|int ...$values)
    {
        parent::__construct(
            array_map(fn($value) => ($value instanceof ushort) ? $value : ushort($value), $values)
        );
    }
}
