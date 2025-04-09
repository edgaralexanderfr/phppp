<?php

declare(strict_types=1);

namespace PHPTypes\Primitive;

use PHPTypes\ArrayType;

class IntArray extends ArrayType
{
    protected ?string $type = 'integer';

    public function __construct(int ...$values)
    {
        parent::__construct($values);
    }
}
