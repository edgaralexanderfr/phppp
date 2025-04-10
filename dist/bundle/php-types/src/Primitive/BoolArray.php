<?php

declare(strict_types=1);

namespace PHPTypes\Primitive;

use PHPTypes\ArrayType;

class BoolArray extends ArrayType
{
    protected ?string $type = 'boolean';

    public function __construct(bool ...$values)
    {
        parent::__construct($values);
    }
}
