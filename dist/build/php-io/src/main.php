<?php

declare(strict_types=1);

if (!defined('PHPIO_IGNORE_MAIN') || !PHPIO_IGNORE_MAIN) {
    register_shutdown_function(function () {
        if ((!defined('PHPTYPES_IGNORE_MAIN') || !PHPIO_IGNORE_MAIN) && function_exists('main')) {
            global $argc, $argv;

            if (isset($argc) && isset($argv)) {
                if (function_exists('PHPTypes\Primitive\string_array')) {
                    $args = PHPTypes\Primitive\string_array(...$argv);
                } else {
                    $args = $argv;
                }

                exit((int) main((int) $argc, $args));
            } else {
                if (function_exists('PHPTypes\Primitive\string_array')) {
                    $args = PHPTypes\Primitive\string_array();
                } else {
                    $args = $argv;
                }

                exit((int) main(0, $args));
            }
        }
    });
}
