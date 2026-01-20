<?php

final class Logger
{
    private const LOG_FILE = __DIR__ . '/../logs/app.log';

    public static function info(string $message, array $context = []): void
    {
        self::write('INFO', $message, $context);
    }

    public static function error(string $message, array $context = []): void
    {
        self::write('ERROR', $message, $context);
    }

    private static function write(string $level, string $message, array $context): void
    {
        $dir = dirname(self::LOG_FILE);
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $line = json_encode([
            'ts' => (new DateTimeImmutable('now'))->format(DateTimeInterface::ATOM),
            'level' => $level,
            'message' => $message,
            'context' => $context,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        if (!is_string($line)) {
            return;
        }

        @file_put_contents(self::LOG_FILE, $line . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}

