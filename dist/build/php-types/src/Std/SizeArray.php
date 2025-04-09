<?php

declare(strict_types=1);

namespace PHPTypes\Std;

use PHPTypes\ArrayObject;

class SizeArray extends ArrayObject
{
    protected array $object = [
        SizeType::class => SizeType::class,
        size_t::class => size_t::class,
    ];

    public function __construct(SizeType|size_t|int ...$values)
    {
        parent::__construct(
            array_map(fn($value) => ($value instanceof SizeType || $value instanceof size_t) ? $value : size_t($value), $values)
        );
    }
}
