<?php

declare(strict_types=1);

namespace PHPTypes\Primitive;

use stdClass;

class ObjectType extends stdClass implements JSONInterface
{
    public function __construct(array|stdClass $values = [])
    {
        foreach ($values as $property => $value) {
            $this->{$property} = $value;
        }
    }

    public function json(int $flags = 0, int $depth = 512): string|false|null
    {
        return json_encode($this, $flags, $depth);
    }
}
