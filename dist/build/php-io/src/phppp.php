<?php

declare(strict_types=1);

// Check and `define` `global` configuration from repositories:
if (!defined('PHPPP_CONFIG')) {
    $working_directory = getcwd();
    $directory = $working_directory;
    $config_file_found = false;

    /** @todo We gotta handle Windows case in here... */
    do {
        $config_file_path = $directory . DIRECTORY_SEPARATOR . 'phppp.json';

        if (file_exists($config_file_path)) {
            $config_file_found = true;

            break;
        }

        $directory = realpath($directory . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR);
    } while ($directory != DIRECTORY_SEPARATOR);

    if (!$config_file_found || !is_object($config = json_decode((string) file_get_contents($config_file_path)))) {
        $config = (object)[];
    }

    define('PHPPP_CONFIG', $config);

    if ($config_file_found) {
        define('PHPPP_CONFIG_PATH', $directory);
    }
}

// Define `PHPIO` configs:

if (!defined('APP')) {
    $app_path = PHPPP_CONFIG->io?->app_path ?? '';

    if (is_string($app_path)) {
        $app_path = DIRECTORY_SEPARATOR . $app_path . DIRECTORY_SEPARATOR;
    } else {
        $app_path = '';
    }

    if (defined('PHPPP_CONFIG_PATH')) {
        define('APP', ($path = realpath(PHPPP_CONFIG_PATH . $app_path)) ? $path : PHPPP_CONFIG_PATH);
    } else if (isset($_SERVER['DOCUMENT_ROOT']) && !empty($_SERVER['DOCUMENT_ROOT'])) {
        define('APP', ($path = realpath($_SERVER['DOCUMENT_ROOT'] . $app_path)) ? $path : $_SERVER['DOCUMENT_ROOT']);
    } else {
        $cwd = getcwd();

        define('APP', ($path = realpath($cwd . $app_path)) ? $path : $cwd);
    }
}

$ini = PHPPP_CONFIG?->io?->ini ?? (object)[];

if (is_object($ini)) {
    foreach ($ini as $option => $value) {
        ini_set($option, $value);
    }
}

$ignore_main = PHPPP_CONFIG->io?->config?->ignore_main ?? false;
$add_leading_slash = PHPPP_CONFIG->io?->config?->import?->add_leading_slash ?? true;
$add_php_extension = PHPPP_CONFIG->io?->config?->import?->add_php_extension ?? true;
$colorize = PHPPP_CONFIG?->io?->config?->console?->colorize ?? null;

if (!defined('PHPIO_IGNORE_MAIN')) {
    define('PHPIO_IGNORE_MAIN', $ignore_main ? true : false);
}

if (!defined('PHPIO_ADD_LEADING_SLASH')) {
    define('PHPIO_ADD_LEADING_SLASH', $add_leading_slash ? true : false);
}

if (!defined('PHPIO_ADD_PHP_EXTENSION')) {
    define('PHPIO_ADD_PHP_EXTENSION', $add_php_extension ? true : false);
}

if (is_bool($colorize)) {
    \PHPIO\Console::colorize($colorize);
}

// `unset` `global` `PHPIO` variables:

unset($colorize);
unset($add_php_extension);
unset($add_leading_slash);
unset($ignore_main);
unset($value);
unset($option);
unset($ini);
unset($cwd);
unset($path);
unset($app_path);

// `unset` `global` variables from repositories:

unset($config);
unset($directory);
unset($config_file_found);
unset($config_file_path);
unset($working_directory);
