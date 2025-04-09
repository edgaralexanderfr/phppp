<?php

declare(strict_types=1);

namespace PHPIO;

function concat(mixed ...$output): string
{
    return format(' ', ...$output);
}
