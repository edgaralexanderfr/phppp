<?php

declare(strict_types=1);

namespace PHPTypes\Primitive;

use PHPTypes\ArrayType;

class StringArray extends ArrayType
{
    protected ?string $type = 'string';

    public function __construct(string ...$values)
    {
        parent::__construct($values);
    }
}
