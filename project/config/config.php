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
    if (empty($db['host']) || empty($db['dbname']) || empty($db['user']) || empty($db['pass'])) {
        throw new \Shortener\Exception\InternalException('Error Establishing a Database Connection');
    }
    try {
        $pdo = new \PDO("mysql:host=" . $db['host'] . ";dbname=" . $db['dbname'], $db['user'], $db['pass']);
    } catch (\PDOException $e) {
        throw new \Shortener\Exception\InternalException($e->getMessage(), $e->getCode());
    }
    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    return $pdo;
};

$config['view'] = function ($c) {
    $view = new \Smarty;
    $view->setTemplateDir(ROOT_PATH . 'templates/');
    $view->setCompileDir('/var/www/templates_cmpl/');
    $view->setDebugging(false);
    $view->setCompileCheck(false);
    $view->setForceCompile(false);
    return $view;
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
