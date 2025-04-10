<?php

declare(strict_types=1);

namespace PHPTypes;

use \ArrayIterator;
use \TypeError;
use PHPTypes\Returnable\multiple;

use function PHPTypes\Returnable\multiple;

class ArrayObject extends ArrayIterator
{
    protected array $object = [];

    #[\Override]
    /** @disregard */
    public function offsetSet(mixed $offset, mixed $value): void
    {
        if ($this->object && (($type = gettype($value)) != 'object' || !isset($this->object[$type = $value::class]))) {
            $types = implode('|', $this->object);

            throw new TypeError("Element must be of type {$types}, {$type} given, called");
        }

        parent::offsetSet($offset, $value);
    }

    /** @disregard */
    public function isEmpty(): bool
    {
        return $this->count() == 0;
    }

    /** @disregard */
    public function hasAny(): bool
    {
        return $this->count() > 0;
    }

    /**
     * Where:
     *
     * ```php
     * <?php
     *
     * return multiple(...[
     *   0 => new ArrayIterator($resultant_elements = array()), // An `ArrayIterator|ArrayObject` of type `get_class($this)` consisting of the resultant elements.
     *   1 => new ArrayIterator($extracted_elements = array()), // An `ArrayIterator|ArrayObject` of type `get_class($this)` consisting of the extracted elements.
     * ]);
     * ```
     *
     * @disregard
     */
    public function splice(int $offset, ?int $length = null, mixed $replacement = []): multiple
    {
        $class = get_class($this);
        $array = (array) $this;
        $extracted_elements = array_splice($array, $offset, $length, $replacement);

        return multiple(new $class(...$array), new $class(...$extracted_elements));
    }

    /** @disregard */
    public int $length
    {
        /** @disregard */
        get => $this->count();
    }
}
