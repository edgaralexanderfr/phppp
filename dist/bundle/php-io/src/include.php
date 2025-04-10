<?php

declare(strict_types=1);

// Define `PHPIO` configs:

$include = PHPPP_CONFIG->io?->include ?? null;
$files = [];

if (is_string($include)) {
    $files[] = $include;
} else if (is_array($include)) {
    $files = $include;
}

$include_all = function (array $files, string $dir) use (&$include_all) {
    foreach ($files as $file) {
        if (!is_string($file) || $file == '.' || $file == '..') {
            continue;
        }

        $path = $dir . DIRECTORY_SEPARATOR . $file;

        if (is_dir($path)) {
            $files_to_include = scandir($path);
            $include_all($files_to_include ? $files_to_include : [], $path);
        } else if (is_file($path)) {
            $php_extension = strtolower(substr($file, -4));
            $phar_extension = strtolower(substr($file, -5));

            if ($php_extension == '.php' || $phar_extension == '.phar') {
                require_once $path;
            }
        }
    }
};

$include_all($files, APP);

// `unset` `global` `PHPIO` variables:

unset($include_all);
unset($files);
unset($include);
