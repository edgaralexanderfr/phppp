<?php

declare(strict_types=1);

// Check and `define` `global` configuration from repositories:
if (!defined('PHPPP_CONFIG')) {
    $working_directory = getcwd();
    $directory = $working_directory;
    $config_file_found = false;

    /** @todo We gotta handle Windows case in here... */
    do {
        $config_file_path = "{$directory}/phppp.json";

        if (file_exists($config_file_path)) {
            $config_file_found = true;

            break;
        }

        $directory = realpath("{$directory}/../");
    } while ($directory != '/');

    if (!$config_file_found || !is_object($config = json_decode((string) file_get_contents($config_file_path)))) {
        $config = (object)[];
    }

    define('PHPPP_CONFIG', $config);

    if ($config_file_found) {
        define('PHPPP_CONFIG_PATH', $directory);
    }
}

// Define `PHPTypes` configs:

$ignore_typedef_functions = PHPPP_CONFIG?->types?->config?->typedef?->ignore_functions ?? false;

if (!defined('PHPTYPES_IGNORE_TYPEDEF_FUNCTIONS')) {
    define('PHPTYPES_IGNORE_TYPEDEF_FUNCTIONS', $ignore_typedef_functions ? true : false);
}

// `unset` `global` `PHPTypes` variables:

unset($ignore_typedef_functions);

// `unset` `global` variables from repositories:

unset($config);
unset($directory);
unset($config_file_found);
unset($config_file_path);
unset($working_directory);
