<?php

namespace Shortener;

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
    static public function create(\PDO $db, Router $router, Request $request, \Smarty $view)
    {
        $controllerClass = $router->getController($request->getUrl(), $request->getMethod());
        $controller = new $controllerClass($db, $request, $view);
        $controller->setPathVars($router->getPathVars());
        return $controller;
    }
}
