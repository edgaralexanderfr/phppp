<?php

declare(strict_types=1);

namespace PHPIO;

final class std
{
    private static bool $is_cli = false;

    public static function init()
    {
        self::$is_cli = !http_response_code();

        $append = PHPPP_CONFIG?->io?->config?->std?->pout?->append ?? true;

        if (!self::$is_cli && is_bool($append) && !$append) {
            file_put_contents(APP . '/phppp-log.txt', '');
        }
    }

    public static function isCLI(): bool
    {
        return self::$is_cli;
    }

    public static function pout(string $output, string $emoji = '', string $color = ''): void
    {
        $pout = '';

        if ($emoji != '') {
            $pout .= "{$emoji} ";
        }

        if (self::$is_cli) {
            if ($color == '') {
                $pout .= $output;
            } else {
                $pout .= "\033[{$color}m{$output}\033[0m";
            }

            echo $pout;
        } else {
            $pout .= $output;

            file_put_contents(APP . '/phppp-log.txt', $pout, FILE_APPEND);
        }
    }

    public static function pin(?string $prompt = null): string|false
    {
        if (self::$is_cli) {
            return readline($prompt);
        }

        self::pout(($prompt ? $prompt : 'Skipping `readline()`...') . PHP_EOL, '↩️', '32');

        return false;
    }

    public static function halt(string|int $status = ''): never
    {
        if (self::$is_cli) {
            exit($status);
        }

        die($status);
    }
}

std::init();
