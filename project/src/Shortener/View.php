<?php

declare(strict_types=1);


namespace Shortener;


class View extends \Smarty
{
    public function checkIsWritableCompileDir(): bool
    {
        return is_writable($this->getCompileDir());
    }
}
