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
     * @param \ArrayAccess $di
     * @return AbstractController
     * @throws PageNotFound
     */
    static public function create(\ArrayAccess $di): AbstractController
    {
        /** @var Router $request */
        $router = $di['router'];

        /** @var Request $request */
        $request = $di['request'];

        $controllerClass = $router->getController($request->getUrl(), $request->getMethod());

        if (!isset($di[$controllerClass])) {
            throw new PageNotFound('Page Not Found');
        }

        $controller = $di[$controllerClass];
        $controller->setPathVars($router->getPathVars());
        return $controller;
    }
}
