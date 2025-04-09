<?php

declare(strict_types=1);

namespace PHPTypes\Primitive;

use \ArrayIterator;
use \TypeError;

class TupleType extends ArrayIterator
{
    public function __construct(mixed ...$values)
    {
        parent::__construct($values);
    }

    #[\Override]
    public function offsetSet(mixed $offset, mixed $value): void
    {
        $class = get_class($this);

        throw new TypeError("'{$class}' object does not support item assignment, called");
    }
}
