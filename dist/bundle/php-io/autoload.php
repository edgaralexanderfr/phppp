<?php

declare(strict_types=1);

if (!defined('PHPIO')) {
    define('PHPIO', 1);

    function php_io_autoload(string $class): void
    {
        $project_root_namespace = 'PHPIO\\';

        if (!str_contains($class, $project_root_namespace)) {
            return;
        }

        $class_path = str_replace([$project_root_namespace, '\\'], ['', '/'], $class);
        $file_path = __DIR__ . "/src/{$class_path}.php";

        if (file_exists($file_path)) {
            require_once $file_path;
        }
    }

    spl_autoload_register('php_io_autoload');

    include __DIR__ . '/src/import.php';
    include __DIR__ . '/src/format.php';
    include __DIR__ . '/src/ln.php';
    include __DIR__ . '/src/nln.php';
    include __DIR__ . '/src/concat.php';
    include __DIR__ . '/src/println.php';
    include __DIR__ . '/src/phppp.php';
    include __DIR__ . '/src/include.php';
    include __DIR__ . '/src/error_handler.php';
    include __DIR__ . '/src/main.php';
}
