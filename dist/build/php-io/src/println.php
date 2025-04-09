<?php

declare(strict_types=1);

namespace PHPIO;

function println(mixed ...$output): void
{
    $output = concat(...$output);

    std::pout($output . PHP_EOL);
}
