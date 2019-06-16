<?php

namespace Shortener;

use Shortener\Router\PageNotFound;

/**
 * Application
 *
 * @package Shortener
 */
class Application
{
    /**
     * @var \ArrayAccess
     */
    private $di;

    /**
     * Application constructor.
     *
     * @param \ArrayAccess $config
     */
    public function __construct(\ArrayAccess $di)
    {
        $this->di = $di;
    }

    /**
     * Run controllers
     */
    public function run(): Response
    {
        try {
            $controller = ControllerFactory::create($this->di);
            $controller->run();
            return $controller->getResponse();

        } catch (PageNotFound $e) {
            return (new Response())->setHttpCode(Response::STATUS_NOT_FOUND);

        } catch (\Exception $e) {
            return (new Response())->setHttpCode(Response::STATUS_INTERNAL_ERROR);
        }
    }
}
