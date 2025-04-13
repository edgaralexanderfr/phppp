<?php

declare(strict_types=1);

include __DIR__ . '/../dist/phppp';

use PHPIO\Console;
use PHPIO\File;
use PHPIO\Path;
use PHPTypes\Primitive\string_array;

function main(int $argc, string_array $argv): int
{
    $entrypoint = ($argv[1] ?? '');
    $second_command = ($argv[2] ?? '');
    $options = getopt('v::', ['version::']);

    /** @var bool */
    $version = isset($options['v']) || isset($options['version']);
    /** @var bool */
    $init = $entrypoint == 'init';
    /** @var bool */
    $install = $entrypoint == 'install';
    /** @var bool */
    $uninstall = $entrypoint == 'uninstall';
    /** @var bool */
    $dry_run = $second_command == '-dr' || $second_command == '--dry-run';

    if ($dry_run) {
        Console::warn('Warning: Running in `--dry-run` mode.  No files will be altered during the execution of the process...');
    }

    if ($version) {
        return version();
    }

    if ($init) {
        return init($dry_run);
    }

    if ($install) {
        return install($dry_run);
    }

    if ($uninstall) {
        return uninstall($dry_run);
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

function init(bool $dry_run = false): int
{
    $working_directory = getcwd();

    $phppp_json_path = "{$working_directory}/phppp.json";
    $phppp_log_text_path = "{$working_directory}/phppp-log.txt";

    if (!file_exists($phppp_json_path)) {
        if (!$dry_run) {
            file_put_contents($phppp_json_path, '{}');
        }

        Console::log('Generated: ', $phppp_json_path);
    } else {
        Console::warn('Skipping: ', $phppp_json_path);
    }

    if (!file_exists($phppp_log_text_path)) {
        if (!$dry_run) {
            file_put_contents($phppp_log_text_path, '');
        }

        Console::log('Generated: ', $phppp_log_text_path);
    } else {
        Console::warn('Skipping: ', $phppp_log_text_path);
    }

    Console::writeln();
    Console::log('PHP++ project initialized successfully');

    return 0;
}

function install(bool $dry_run = false): int
{
    $include_paths = explode(':', get_include_path());
    $valid_include_paths = [];

    foreach ($include_paths as $include_path) {
        if ($include_path != '.' && $include_path != '..') {
            $valid_include_paths[] = $include_path;
        }
    }

    if (!$valid_include_paths) {
        Console::error('No valid `include_path` found to begin the installation.  Aborting...');
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

    if (!$dry_run) {
        try {
            $src_path->copy($installation_path);
        } catch (Exception) {
        }
    }

    Console::log('🐘 php++', $version, 'has been installed at:', $installation_path);
    Console::log('For more information, please visit: https://github.com/edgaralexanderfr/phppp 🐱🐙');

    return 0;
}

function uninstall(bool $dry_run = false): int
{
    $include_paths = explode(':', get_include_path());
    $valid_include_paths = [];

    foreach ($include_paths as $include_path) {
        if ($include_path != '.' && $include_path != '..') {
            $valid_include_paths[] = $include_path;
        }
    }

    if (!$valid_include_paths) {
        Console::error('No valid `include_path` found to uninstall from. Aborting...');
        Console::log('For more info: run `php -r "echo get_include_path() . PHP_EOL;"` in a new terminal to determine the configured `include_path`.');

        return 1;
    }

    $success = false;

    foreach ($valid_include_paths as $include_path) {
        $phppp_header_file_path = $include_path . DIRECTORY_SEPARATOR . 'phppp';

        if (!file_exists($phppp_header_file_path)) {
            Console::warn('No phppp header found under:', $include_path);
            Console::warn('Skipping...');

            continue;
        }

        $installation_path = new Path($include_path);

        if ($installation_path->full_path === false) {
            Console::error('Invalid `include_path`:', $include_path);
            Console::error('Path not found.');
            Console::warn('Skipping...');

            continue;
        }

        Console::log('Unlinking headers from:', $include_path);

        $phppp_header_file = new File($phppp_header_file_path);
        $php_io_header_file = new File($include_path . DIRECTORY_SEPARATOR . 'php-io');
        $php_types_header_file = new File($include_path . DIRECTORY_SEPARATOR . 'php-types');
        $php_http_header_file = new File($include_path . DIRECTORY_SEPARATOR . 'php-http');

        if ($phppp_header_file->full_path !== false) {
            Console::log('Unlinking:', $phppp_header_file->full_path);

            if (!$dry_run) {
                $phppp_header_file->unlink();
            }
        }

        if ($php_io_header_file->full_path !== false) {
            Console::log('Unlinking:', $php_io_header_file->full_path);

            if (!$dry_run) {
                $php_io_header_file->unlink();
            }
        }

        if ($php_types_header_file->full_path !== false) {
            Console::log('Unlinking:', $php_types_header_file->full_path);

            if (!$dry_run) {
                $php_types_header_file->unlink();
            }
        }

        if ($php_http_header_file->full_path !== false) {
            Console::log('Unlinking:', $php_http_header_file->full_path);

            if (!$dry_run) {
                $php_http_header_file->unlink();
            }
        }

        Console::log('Uninstalling...');

        $bundle_path = new Path($include_path . DIRECTORY_SEPARATOR . 'bundle');

        if ($bundle_path->full_path !== false) {
            Console::log('Uninstalling from:', $bundle_path->full_path);

            if (!$dry_run) {
                $bundle_path->rm();
            }

            $success = true;
        } else {
            Console::warn('No valid installation path found under:', $include_path);
            Console::warn('Skipping...');
        }
    }

    if ($success) {
        Console::log('php++ has been uninstalled from your system.');
    } else {
        Console::log('No php++ installation has been removed from your system.');
    }

    Console::log('For more information, please visit: https://github.com/edgaralexanderfr/phppp 🐱🐙');

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
