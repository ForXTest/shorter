<?php

declare(strict_types=1);

namespace Shortener;

class Logger implements LoggerInterface
{
    public function log(string $message): void
    {
        error_log($message);
    }

    public function logException(\Throwable $e): void
    {
        error_log($e);
    }
}
