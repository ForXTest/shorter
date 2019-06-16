<?php

namespace Shortener\Controller;

use Shortener\Exception;
use Shortener\LoggerInterface;
use Shortener\Request;
use Shortener\Response;
use Shortener\View;

/**
 * Class AbstractController
 *
 * @package Shortener\Controller
 */
abstract class AbstractController
{
    /**
     * @var View
     */
    protected $view;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var array
     */
    protected $pathVars = [];

    /**
     * AbstractController constructor.
     *
     * @param Request $request
     * @param View $view
     * @throws \Exception
     */
    public function __construct(
        Request $request,
        View $view,
        LoggerInterface $logger
    ) {
        $this->request = $request;
        $this->logger = $logger;
        $this->setView($view);
        $this->response = new Response();
    }

    /**
     * Processing request
     *
     * @return mixed
     */
    abstract public function run();

    /**
     * Set variables from a request url
     *
     * @param array $pathVars
     */
    public function setPathVars(array $pathVars): self
    {
        $this->pathVars = $pathVars;
        return $this;
    }

    /**
     * Get a response object
     *
     * @return Response
     */
    public function getResponse(): Response
    {
        return $this->response;
    }

    /**
     * Get variables from a request url
     *
     * @param string|int $key
     * @param mixed $default
     * @return mixed|null
     */
    protected function getPathVar($key, $default = null)
    {
        return $this->pathVars[$key] ?? $default;
    }

    /**
     * Set a template engine
     *
     * @param \Smarty $view
     * @throws Exception
     */
    private function setView(\Smarty $view): self
    {
        $this->view = $view;

        if (!$this->view->checkIsWritableCompileDir()) {
            throw new Exception('The compile directory must be writable');
        }

        return $this;
    }
}
