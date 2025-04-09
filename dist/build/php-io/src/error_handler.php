<?php

declare(strict_types=1);

namespace PHPIO;

use Throwable;

enum ErrorType
{
    case WARNING;
    case ERROR;
    case OTHER;
}

$enable_error_handler = PHPPP_CONFIG->io?->config?->error_handler?->enable ?? true;

if (!is_bool($enable_error_handler)) {
    $enable_error_handler = true;
}

if ($enable_error_handler) {
    set_error_handler(function (int $errno, string $errstr, string $errfile, int $errline) {
        if (!(error_reporting() & $errno) || !ini_get('display_errors')) {
            return true;
        }

        $error = match ($errno) {
            E_ERROR => (object)[
                'tag_type' => 'Error',
                'error_type' => ErrorType::ERROR,
                'halt' => true,
            ],
            E_WARNING => (object)[
                'tag_type' => 'Warning',
                'error_type' => ErrorType::WARNING,
                'halt' => false,
            ],
            E_PARSE => (object)[
                'tag_type' => 'ParseError',
                'error_type' => ErrorType::ERROR,
                'halt' => true,
            ],
            E_NOTICE => (object)[
                'tag_type' => 'Notice',
                'error_type' => ErrorType::WARNING,
                'halt' => false,
            ],
            E_CORE_ERROR => (object)[
                'tag_type' => 'CoreError',
                'error_type' => ErrorType::ERROR,
                'halt' => true,
            ],
            E_CORE_WARNING => (object)[
                'tag_type' => 'CoreWarning',
                'error_type' => ErrorType::WARNING,
                'halt' => false,
            ],
            E_COMPILE_ERROR => (object)[
                'tag_type' => 'CompileError',
                'error_type' => ErrorType::ERROR,
                'halt' => true,
            ],
            E_COMPILE_WARNING => (object)[
                'tag_type' => 'CompileWarning',
                'error_type' => ErrorType::WARNING,
                'halt' => false,
            ],
            E_USER_ERROR => (object)[
                'tag_type' => 'UserError',
                'error_type' => ErrorType::ERROR,
                'halt' => true,
            ],
            E_USER_WARNING => (object)[
                'tag_type' => 'UserWarning',
                'error_type' => ErrorType::WARNING,
                'halt' => false,
            ],
            E_USER_NOTICE => (object)[
                'tag_type' => 'UserNotice',
                'error_type' => ErrorType::WARNING,
                'halt' => false,
            ],
            E_RECOVERABLE_ERROR => (object)[
                'tag_type' => 'RecoverableError',
                'error_type' => ErrorType::ERROR,
                'halt' => true,
            ],
            E_DEPRECATED => (object)[
                'tag_type' => 'Deprecated',
                'error_type' => ErrorType::WARNING,
                'halt' => false,
            ],
            E_USER_DEPRECATED => (object)[
                'tag_type' => 'UserDeprecated',
                'error_type' => ErrorType::WARNING,
                'halt' => false,
            ],
            default => (object)[
                'tag_type' => '',
                'error_type' => ErrorType::OTHER,
                'halt' => true,
            ],
        };

        \PHPIO\error_handler(
            file_path: $errfile,
            line: $errline,
            tag_type: $error->tag_type,
            error_message: $errstr,
            error_type: $error->error_type,
            halt: $error->halt,
            stack_trace: debug_backtrace(),
        );

        return true;
    });

    set_exception_handler(function (Throwable $ex) {
        if (!(error_reporting() & E_ERROR) || !ini_get('display_errors')) {
            return;
        }

        \PHPIO\error_handler(
            file_path: $ex->getFile(),
            line: $ex->getLine(),
            tag_type: $ex::class,
            error_message: $ex->getMessage(),
            error_type: ErrorType::ERROR,
            halt: false,
            stack_trace: $ex->getTrace(),
        );
    });
}

function error_handler(
    string $file_path,
    int $line,
    string $tag_type,
    string $error_message,
    ErrorType $error_type = ErrorType::ERROR,
    bool $halt = false,
    array $stack_trace = []
): void {
    $error = match ($error_type) {
        ErrorType::WARNING => (object)[
            'emoji' => '🟡',
            'color' => '33',
        ],
        ErrorType::ERROR => (object)[
            'emoji' => '⛔',
            'color' => '31',
        ],
        default => (object)[
            'emoji' => '',
            'color' => '',
        ],
    };

    $tag = '';

    if ($tag_type) {
        $tag = "{$tag_type}: ";
    }

    $formatted_message = ln(
        "{$file_path}:{$line}",
        '',
        "{$tag}{$error_message}",
        ...array_map(function ($call) {
            $function = $call->function ?? $call['function'] ?? null;
            $file = $call->file ?? $call['file'] ?? null;
            $line = $call->line ?? $call['line'] ?? null;
            $call_line = '';

            if ($function || $file || $line) {
                $call_line .= '  at ';
            }

            if ($function) {
                $call_line .= "{$function} ";
            }

            if ($file) {
                $call_line .= "({$file}";
            }

            if ($line) {
                $call_line .= ":{$line}";
            }

            if ($file) {
                $call_line .= ')';
            }

            return $call_line;
        }, $stack_trace),
    );

    $formatted_message = ln($formatted_message, '', '');

    std::pout($formatted_message, $error->emoji, $error->color);

    if ($halt) {
        std::halt();
    }
}

unset($enable_error_handler);
