<?php

declare(strict_types=1);

define('ROOT_PATH', dirname(__FILE__, 3) . DIRECTORY_SEPARATOR);

require_once ROOT_PATH . 'vendor/autoload.php';

set_include_path(
    implode(
        PATH_SEPARATOR,
        [
            ROOT_PATH,
            ROOT_PATH . 'scripts' . DIRECTORY_SEPARATOR,
            ROOT_PATH . 'tests/unit' . DIRECTORY_SEPARATOR,
            ROOT_PATH . 'tests/unit' . DIRECTORY_SEPARATOR . 'fixtures' . DIRECTORY_SEPARATOR,
            get_include_path()
        ]
    )
);
