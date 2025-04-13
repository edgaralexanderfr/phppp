<?php

declare(strict_types=1);

include __DIR__ . '/../dist/phppp';

use PHPIO\Console;
use PHPIO\Path;
use PHPTypes\Primitive\string_array;

function main(int $argc, string_array $argv): int
{
    $entrypoint = ($argv[1] ?? '');
    $options = getopt('v::', ['version::']);

    /** @var bool */
    $version = isset($options['v']) || isset($options['version']);
    /** @var bool */
    $init = $entrypoint == 'init';
    /** @var bool */
    $install = $entrypoint == 'install';
    /** @var bool */
    $uninstall = $entrypoint == 'uninstall';

    if ($version) {
        return version();
    }

    if ($init) {
        return init();
    }

    if ($install) {
        return install();
    }

    if ($uninstall) {
        return uninstall();
    }

    return help();
}

function help(): int
{
    $help_file = file_get_contents(__DIR__ . '/help.txt');

    Console::echo(str_replace('${VERSION}', trim(get_version()), $help_file));

    return 0;
}

function version(): int
{
    $version_file = get_version();

    Console::echo($version_file);

    return 0;
}

function init(): int
{
    $working_directory = getcwd();

    $phppp_json_path = "{$working_directory}/phppp.json";
    $phppp_log_text_path = "{$working_directory}/phppp-log.txt";

    if (!file_exists($phppp_json_path)) {
        file_put_contents($phppp_json_path, '{}');

        Console::log('Generated: ', $phppp_json_path);
    } else {
        Console::warn('Skipping: ', $phppp_json_path);
    }

    if (!file_exists($phppp_log_text_path)) {
        file_put_contents($phppp_log_text_path, '');

        Console::log('Generated: ', $phppp_log_text_path);
    } else {
        Console::warn('Skipping: ', $phppp_log_text_path);
    }

    Console::writeln();
    Console::log('PHP++ project initialized successfully');

    return 0;
}

function install(): int
{
    $include_paths = explode(':', get_include_path());
    $valid_include_paths = [];

    foreach ($include_paths as $include_path) {
        if ($include_path != '.' && $include_path != '..') {
            $valid_include_paths[] = $include_path;
        }
    }

    if (!$valid_include_paths) {
        Console::error('No valid `include_path` found to begin the installation. Aborting...');
        Console::log('For more info: run `php -r "echo get_include_path() . PHP_EOL;"` in a new terminal to determine the configured `include_path`.');

        return 1;
    }

    foreach ($valid_include_paths as $include_path) {
        $phppp_header_file_path = $include_path . DIRECTORY_SEPARATOR . 'phppp';

        if (file_exists($phppp_header_file_path)) {
            Console::warn('php++ already installed at:', $include_path);
            Console::warn('Aborting...');

            return 0;
        }
    }

    $version = 'v' . get_version();
    $installation_path = new Path($valid_include_paths[0]);
    $src_path = new Path(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'dist');

    Console::warn('Installing php++', $version . '...');

    try {
        $src_path->copy($installation_path);
    } catch (Exception) {
    }

    Console::log('php++', $version, 'has been installed at:', $installation_path);

    return 0;
}

function uninstall(): int
{
    return 0;
}

function get_version(): string
{
    static $version;

    if ($version) {
        return $version;
    }

    $version = file_get_contents(__DIR__ . '/../version.txt');

    return $version;
}
