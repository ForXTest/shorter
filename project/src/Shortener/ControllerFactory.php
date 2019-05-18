<?php

namespace Shortener;

use Shortener\Controller\AbstractController;
use Shortener\Router\PageNotFound;

/**
 * Class ControllerFactory
 *
 * @package Shortener
 */
class ControllerFactory
{

    /**
     * Create a page controller
     *
     * @param \PDO $db
     * @param Router $router
     * @param Request $request
     * @param \Smarty $view
     * @return mixed
     * @throws Router\Exception
     * @throws Router\PageNotFound
     */
    static public function create(Container $di): AbstractController
    {
        $router = $di['router'];
        $request = $di['request'];

        $controllerClass = $router->getController($request->getUrl(), $request->getMethod());

        if (!isset($di[$controllerClass])) {
            throw new PageNotFound('Page Not Found', 400);
        }

        $controller = $di[$controllerClass];
        $controller->setPathVars($router->getPathVars());
        return $controller;
    }
}
