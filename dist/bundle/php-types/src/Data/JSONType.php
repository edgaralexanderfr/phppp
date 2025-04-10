<?php

declare(strict_types=1);

namespace PHPTypes\Data;

use ArrayAccess;
use JsonSerializable;
use stdClass;
use Stringable;

use PHPTypes\Primitive\JSONInterface;

class JSONType extends stdClass implements ArrayAccess, JsonSerializable, Stringable, JSONInterface
{
    public function __construct(array|stdClass|string $values = [])
    {
        if (is_string($values)) {
            $values = json_decode($values, true) ?? [];
        }

        $class = get_class($this);

        foreach ($values as $property => $value) {
            if (is_array($value) || $value instanceof stdClass) {
                $this->{$property} = new $class($value);
            } else {
                $this->{$property} = $value;
            }
        }
    }

    public function offsetExists(mixed $offset): bool
    {
        return isset($this->{$offset});
    }

    public function offsetGet(mixed $offset): mixed
    {
        return $this->{$offset};
    }

    public function offsetSet(mixed $offset, mixed $value): void
    {
        $this->{$offset} = $value;
    }

    public function offsetUnset(mixed $offset): void
    {
        unset($this->{$offset});
    }

    public function jsonSerialize(): mixed
    {
        if (array_is_list($array = (array) $this)) {
            return $array;
        }

        return $this;
    }

    public function __toString(): string
    {
        $json = $this->json();

        if ($json === false) {
            return 'false';
        }

        if ($json === null) {
            return 'null';
        }

        return $json;
    }

    public function json(int $flags = 0, int $depth = 512): string|false|null
    {
        $to_encode = $this;
        $array = (array) $this;

        if (array_is_list($array)) {
            $to_encode = $array;
        }

        return json_encode($to_encode, $flags, $depth);
    }
}
