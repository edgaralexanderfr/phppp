<?php

declare(strict_types=1);

namespace PHPTypes\Primitive;

interface JSONInterface
{
    public function json(int $flags = 0, int $depth = 512): string|false|null;
}
