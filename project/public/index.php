<?php
/**
 * Entrance
 */

define('ROOT_PATH', dirname(__FILE__, 2) . DIRECTORY_SEPARATOR);

require_once ROOT_PATH . 'vendor/autoload.php';
require_once ROOT_PATH . 'config/config.php';

echo (new \Shortener\Application(new \Shortener\Container($config)))
    ->run();

