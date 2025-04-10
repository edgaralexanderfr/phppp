<?php

declare(strict_types=1);

if (!defined('PHPTYPES')) {
    define('PHPTYPES', 1);

    function php_types_autoload(string $class): void
    {
        $project_root_namespace = 'PHPTypes\\';

        if (!str_contains($class, $project_root_namespace)) {
            return;
        }

        $class_path = str_replace([$project_root_namespace, '\\'], ['', '/'], $class);
        $file_path = __DIR__ . "/src/{$class_path}.php";

        if (file_exists($file_path)) {
            require_once $file_path;
        }
    }

    spl_autoload_register('php_types_autoload');

    include __DIR__ . '/src/Data/types.php';
    include __DIR__ . '/src/Std/types.php';
    include __DIR__ . '/src/Primitive/types.php';
    include __DIR__ . '/src/Returnable/types.php';
    include __DIR__ . '/src/typedef.php';
    include __DIR__ . '/src/typeof.php';
    include __DIR__ . '/src/phppp.php';
}
