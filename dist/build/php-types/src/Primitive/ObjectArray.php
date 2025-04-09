<?php

declare(strict_types=1);

namespace PHPTypes\Primitive;

use PHPTypes\ArrayObject;

class ObjectArray extends ArrayObject
{
    protected array $object = [
        ObjectType::class => ObjectType::class,
        object_t::class => object_t::class,
    ];

    public function __construct(ObjectType|object_t ...$values)
    {
        parent::__construct($values);
    }
}
