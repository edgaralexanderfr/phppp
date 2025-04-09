<?php

declare(strict_types=1);

namespace PHPTypes\Primitive;

use stdClass;

use PHPTypes\Std\int16_t;
use PHPTypes\Std\uint8_t;
use PHPTypes\Std\uint16_t;

class object_t extends ObjectType {};
class bool_array extends BoolArray {};
class int_array extends IntArray {};
class double_array extends DoubleArray {};
class float_array extends FloatArray {};
class string_array extends StringArray {};
class object_array_t extends ObjectArray {};
class byte extends uint8_t {};
class char extends CharType {};
class uchar extends UCharType {};
class short extends int16_t {};
class ushort extends uint16_t {};
class tuple extends TupleType {};
class byte_array extends ByteArray {};
class char_array extends CharArray {};
class uchar_array extends UCharArray {};
class short_array extends ShortArray {};
class ushort_array extends UShortArray {};
class tuple_array extends TupleArray {};

function object_t(array|stdClass $values): object_t
{
    return new object_t($values);
}

function bool_array(bool ...$values): bool_array
{
    return new bool_array(...$values);
}

function int_array(int ...$values): int_array
{
    return new int_array(...$values);
}

function double_array(float ...$values): double_array
{
    return new double_array(...$values);
}

function float_array(float ...$values): float_array
{
    return new float_array(...$values);
}

function string_array(string ...$values): string_array
{
    return new string_array(...$values);
}

function object_array_t(object_t ...$values): object_array_t
{
    return new object_array_t(...$values);
}

function byte(int $value): byte
{
    return new byte($value);
}

function char(string|int $value): char
{
    return new char($value);
}

function uchar(string|int $value): uchar
{
    return new uchar($value);
}

function short(int $value): short
{
    return new short($value);
}

function ushort(int $value): ushort
{
    return new ushort($value);
}

function tuple(mixed ...$values): tuple
{
    return new tuple(...$values);
}

function byte_array(byte|int ...$values): byte_array
{
    return new byte_array(...$values);
}

function char_array(CharType|char|string|int ...$values): char_array
{
    return new char_array(...$values);
}

function uchar_array(UCharType|uchar|string|int ...$values): uchar_array
{
    return new uchar_array(...$values);
}

function short_array(short|int ...$values): short_array
{
    return new short_array(...$values);
}

function ushort_array(ushort|int ...$values): ushort_array
{
    return new ushort_array(...$values);
}

function tuple_array(TupleType|tuple ...$values): tuple_array
{
    return new tuple_array(...$values);
}
