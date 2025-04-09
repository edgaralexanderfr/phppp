<?php

declare(strict_types=1);

namespace PHPIO;

final class System
{
    private static bool $is_windows = false;

    public static function init()
    {
        self::$is_windows = str_contains(PHP_OS, 'WIN');
    }

    public static function isWindows(): bool
    {
        return self::$is_windows;
    }

    public static function pause(string $message = "Press any key to continue...\n"): void
    {
        if (!std::isCLI()) {
            std::pout(($message ? $message : 'Skipping `\PHPIO\System::pause()`...') . PHP_EOL, '↩️', '32');

            return;
        }

        if (self::$is_windows) {
            system('pause');
        } else {
            system("read -n 1 -s -p \"{$message}\"");
        }
    }
}

System::init();
