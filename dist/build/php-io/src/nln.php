<?php

declare(strict_types=1);

namespace PHPIO;

function nln(mixed ...$output): string
{
    return format("\r\n", ...$output);
}
