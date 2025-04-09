<?php

declare(strict_types=1);

namespace PHPTypes\Primitive;

use PHPTypes\ArrayObject;

class TupleArray extends ArrayObject
{
    protected array $object = [
        TupleType::class => TupleType::class,
        tuple::class => tuple::class,
    ];

    public function __construct(TupleType|tuple ...$values)
    {
        parent::__construct($values);
    }
}
