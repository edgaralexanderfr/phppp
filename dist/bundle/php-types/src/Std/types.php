<?php

declare(strict_types=1);

namespace PHPTypes\Std;

class int8_t extends Int8Type {};
class uint8_t extends UInt8Type {};
class int16_t extends Int16Type {};
class uint16_t extends UInt16Type {};
class size_t extends SizeType {};
class int8_array extends Int8Array {};
class uint8_array extends UInt8Array {};
class int16_array extends Int16Array {};
class uint16_array extends UInt16Array {};
class size_array extends SizeArray {};

function int8_t(int $value): int8_t
{
    return new int8_t($value);
}

function uint8_t(int $value): uint8_t
{
    return new uint8_t($value);
}

function int16_t(int $value): int16_t
{
    return new int16_t($value);
}

function uint16_t(int $value): uint16_t
{
    return new uint16_t($value);
}

function size_t(int $value): size_t
{
    return new size_t($value);
}

function int8_array(Int8Type|int8_t|int ...$values): int8_array
{
    return new int8_array(...$values);
}

function uint8_array(UInt8Type|uint8_t|int ...$values): uint8_array
{
    return new uint8_array(...$values);
}

function int16_array(Int16Type|int16_t|int ...$values): int16_array
{
    return new int16_array(...$values);
}

function uint16_array(UInt16Type|uint16_t|int ...$values): uint16_array
{
    return new uint16_array(...$values);
}

function size_array(SizeType|size_t|int ...$values): size_array
{
    return new size_array(...$values);
}
