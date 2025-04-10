<?php

declare(strict_types=1);

namespace PHPTypes\Returnable;

use PHPTypes\ArrayObject;

class MultipleArray extends ArrayObject
{
    protected array $object = [
        MultipleType::class => MultipleType::class,
        multiple::class => multiple::class,
    ];

    public function __construct(MultipleType|multiple ...$values)
    {
        parent::__construct($values);
    }
}
