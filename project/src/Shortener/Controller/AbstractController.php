<?php

namespace Shortener\Controller;

use Shortener\Exception;
use Shortener\Request;
use Shortener\Response;

/**
 * Class AbstractController
 *
 * @package Shortener\Controller
 */
abstract class AbstractController
{
    /**
     * @var \PDO
     */
    protected $db;

    /**
     * @var \Smarty
     */
    protected $view;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var array
     */
    protected $pathVars = [];

    /**
     * AbstractController constructor.
     *
     * @param \PDO $db
     * @param Request $request
     * @param \Smarty $view
     * @throws \Exception
     */
    public function __construct(\PDO $db, Request $request, \Smarty $view)
    {
        $this->db = $db;
        $this->request = $request;
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
    public function setPathVars(array $pathVars)
    {
        $this->pathVars = $pathVars;
    }

    /**
     * Get a response object
     *
     * @return Response
     */
    public function getResponse() : Response
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
     * Log a exception
     *
     * @param \Throwable $e
     */
    protected function logException(\Throwable $e)
    {
        error_log($e);
    }


    /**
     * Set a template engine
     *
     * @param \Smarty $view
     * @throws Exception
     */
    private function setView(\Smarty $view)
    {
        $this->view = $view;
        if (!is_writable($this->view->getCompileDir())) {
            throw new Exception('The compile directory must be writable');
        }
    }
}
