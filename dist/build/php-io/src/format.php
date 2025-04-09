<?php

declare(strict_types=1);

namespace PHPIO;

function format(string $separator = '', mixed ...$output): string
{
    $output_string = implode($separator, array_map(function ($output) {
        if ($output === null) {
            return 'null';
        }

        if ($output === false) {
            return 'false';
        }

        if ($output === true) {
            return 'true';
        }

        if (is_resource($output)) {
            return 'resource';
        }

        if (is_object($output) && method_exists($output, '__toString')) {
            return (string) $output;
        }

        if (is_iterable($output)) {
            $new_output = json_encode((array) $output);

            if ($new_output !== false) {
                return $new_output;
            }

            return gettype($output);
        }

        if (is_object($output)) {
            $new_output = json_encode($output);

            if ($new_output !== false) {
                return $new_output;
            }

            return gettype($output);
        }

        return $output;
    }, $output));

    return $output_string;
}
