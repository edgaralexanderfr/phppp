<?php

declare(strict_types=1);

namespace PHPTypes\Set;

use Iterator;

class HashSet implements Iterator
{
    private array $hash_set;

    public function __construct()
    {
        $this->hash_set = [];
    }

    public function getArray(): array
    {
        return $this->hash_set;
    }

    public function add(mixed $value): bool
    {
        if ($this->contains($value)) {
            return false;
        }

        $this->hash_set[$value] = $value;

        return true;
    }

    public function contains(mixed $value): bool
    {
        return isset($this->hash_set[$value]);
    }

    public function unionWith(self $hash_set): void
    {
        $final_hash_set = new self();

        foreach ($this->hash_set as $value) {
            if (!$hash_set->contains($value)) {
                $final_hash_set->add($value);
            }
        }

        foreach ($hash_set as $value) {
            if (!$this->contains($value)) {
                $final_hash_set->add($value);
            }
        }

        $this->hash_set = $final_hash_set->getArray();
        $this->rewind();
    }

    public function intersectWith(self $hash_set): void
    {
        $final_hash_set = new self();

        foreach ($this->hash_set as $value) {
            if ($hash_set->contains($value)) {
                $final_hash_set->add($value);
            }
        }

        foreach ($hash_set as $value) {
            if ($this->contains($value)) {
                $final_hash_set->add($value);
            }
        }

        $this->hash_set = $final_hash_set->getArray();
        $this->rewind();
    }

    public function remove(mixed $value): bool
    {
        if ($this->contains($value)) {
            unset($this->hash_set[$value]);

            return true;
        }

        return false;
    }

    public function rewind(): void
    {
        reset($this->hash_set);
    }

    #[\ReturnTypeWillChange]
    public function current(): mixed
    {
        return current($this->hash_set);
    }

    #[\ReturnTypeWillChange]
    public function key(): mixed
    {
        return key($this->hash_set);
    }

    public function next(): void
    {
        next($this->hash_set);
    }

    public function valid(): bool
    {
        return key($this->hash_set) !== null;
    }
}
