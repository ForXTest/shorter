<?php
/**
 * Entrance
 */

define('ROOT_PATH', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);

require_once ROOT_PATH . 'vendor/autoload.php';
require_once ROOT_PATH . 'config/config.php';

$application = new \Shortener\Application(new \Shortener\Container($config));
echo $application->run();

