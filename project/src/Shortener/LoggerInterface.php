<?php

declare(strict_types=1);

namespace Shortener;

interface LoggerInterface
{
    public function log(string $message): void;

    public function logException(\Throwable $e): void;
}