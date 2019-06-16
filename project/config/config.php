<?php
/**
 * Config
 */
$config = [];

$config['mysql'] = function ($c) {
    return [
        'host' => $_ENV['MYSQL_DB_HOST'] ?? null,
        'user' => $_ENV['MYSQL_USER'] ?? null,
        'pass' => $_ENV['MYSQL_ROOT_PASSWORD'] ?? null,
        'dbname' => $_ENV['MYSQL_DATABASE'] ?? null,
    ];
};

$config['db'] = function ($c) {

    $db = $c['mysql'];

    return new \Shortener\DatabaseMysql(
        $db['host'],
        $db['user'],
        $db['pass'],
        $db['dbname']
    );
};

$config['view'] = function ($c) {
    $view = new \Shortener\View();
    $view->setTemplateDir(ROOT_PATH . 'templates/');
    $view->setCompileDir('/var/www/templates_cmpl/');
    $view->setDebugging(false);
    $view->setCompileCheck(false);
    $view->setForceCompile(false);
    return $view;
};

$config[\Shortener\Logger::class] = function ($c) {
    return new \Shortener\Logger();
};

$config['request'] = function ($c) {
    return new \Shortener\Request();
};

$config['router'] = function ($c) {
    return new Shortener\Router($c['routes']);
};

$config['routes'] = function ($c) {
    return [
        [
            'url' => '/(?<shortUrl>[^?]+)',
            'class' => '\Shortener\Controller\Redirect',
            'methods' => ['GET']
        ],
        [
            'url' => '/',
            'class' => '\Shortener\Controller\Index',
            'methods' => ['GET', 'POST']
        ]
    ];
};

$config[\Shortener\Controller\Index::class] = function ($c) {
    return new \Shortener\Controller\Index(
        $c['db'],
        $c['request'],
        $c['view'],
        $c[\Shortener\Logger::class],
        $c[\Shortener\Data\UrlRegistry::class]
    );
};

$config[\Shortener\Controller\Redirect::class] = function ($c) {
    return new \Shortener\Controller\Redirect(
        $c['db'],
        $c['request'],
        $c['view'],
        $c[\Shortener\Logger::class],
        $c[\Shortener\Data\UrlRegistry::class]
    );
};

$config[\Shortener\Data\UrlRegistry::class] = function ($c) {
    return new \Shortener\Data\UrlRegistry($c['db']);
};
