<?php

declare(strict_types=1);

namespace PHPIO;

function ln(mixed ...$output): string
{
    return format(PHP_EOL, ...$output);
}
