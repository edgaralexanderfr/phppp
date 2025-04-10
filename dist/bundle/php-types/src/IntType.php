<?php

declare(strict_types=1);

namespace PHPTypes;

abstract class IntType implements \Stringable, \JsonSerializable
{
    protected int $val;

    public function __construct(int $value)
    {
        $this->setValue($value);
    }

    public function __serialize(): array
    {
        return [
            'value' => $this->val,
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->setValue((int) $data['value']);
    }

    public function __toString(): string
    {
        return (string) $this->val;
    }

    public function jsonSerialize(): mixed
    {
        return $this->val;
    }

    public function toInt(): int
    {
        return $this->val;
    }

    abstract protected function setValue(int $value): void;
}
