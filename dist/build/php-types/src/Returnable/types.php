<?php

declare(strict_types=1);

namespace PHPTypes\Returnable;

class multiple extends MultipleType {};
class multiple_array extends MultipleArray {};

function multiple(mixed ...$values): multiple
{
    return new multiple(...$values);
}

function multiple_array(MultipleType|multiple ...$values): multiple_array
{
    return new multiple_array(...$values);
}
