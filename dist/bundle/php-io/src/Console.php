<?php

declare(strict_types=1);

namespace PHPIO;

final class Console
{
    private static bool $colorize = true;

    public static function colorize(bool $colorize = true): void
    {
        self::$colorize = $colorize;
    }

    public static function echo(mixed ...$output): void
    {
        $output = concat(...$output);

        std::pout($output . PHP_EOL);
    }

    public static function write(mixed ...$output): void
    {
        $output = concat(...$output);

        std::pout($output);
    }

    public static function writeln(mixed ...$output): void
    {
        $output = concat(...$output);

        std::pout($output . PHP_EOL);
    }

    public static function writeLine(mixed ...$output): void
    {
        $output = concat(...$output);

        std::pout($output . PHP_EOL);
    }

    public static function log(mixed ...$output): void
    {
        $output_string = concat(...$output);

        if (!self::$colorize || System::isWindows()) {
            std::pout($output_string . PHP_EOL);
        } else {
            std::pout($output_string . PHP_EOL, 'ℹ️', '36');
        }
    }

    public static function warn(mixed ...$output): void
    {
        $output_string = concat(...$output);

        if (!self::$colorize || System::isWindows()) {
            std::pout($output_string . PHP_EOL);
        } else {
            std::pout($output_string . PHP_EOL, '🟡', '33');
        }
    }

    public static function error(mixed ...$output): void
    {
        $output_string = concat(...$output);

        if (!self::$colorize || System::isWindows()) {
            std::pout($output_string . PHP_EOL);
        } else {
            std::pout($output_string . PHP_EOL, '⛔', '31');
        }
    }

    public static function readln(?string $prompt = null): string|false
    {
        return std::pin($prompt);
    }

    public static function readLine(?string $prompt = null): string|false
    {
        return std::pin($prompt);
    }
}
