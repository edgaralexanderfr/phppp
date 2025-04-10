<?php

declare(strict_types=1);

include __DIR__ . '/../dist/phppp';

use PHPIO\Console;
use PHPTypes\Primitive\string_array;

function main(int $argc, string_array $argv): int
{
    $entrypoint = ($argv[1] ?? '');
    $options = getopt('v::', ['version::']);

    /** @var bool */
    $version = isset($options['v']) || isset($options['version']);
    /** @var bool */
    $init = $entrypoint == 'init';

    if ($version) {
        return version();
    }

    if ($init) {
        return init();
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

function get_version(): string
{
    static $version;

    if ($version) {
        return $version;
    }

    $version = file_get_contents(__DIR__ . '/../version.txt');

    return $version;
}
