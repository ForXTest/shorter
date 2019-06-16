<?php

declare(strict_types=1);

namespace Shortener;

interface DatabaseInterface
{
    public function getConnection();
}