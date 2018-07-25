<?php
namespace Shortener;

use Shortener\Router\Exception;
use Shortener\Router\PageNotFound;

/**
 * Class Router
 *
 * @package Shortener
 */
class Router
{
    /**
     * Array of routes
     *
     * @var array
     */
    private $routes = [];

    /**
     * Vars from request uri
     *
     * @var array
     */
    private $_pathVars = [];

    /**
     * Router constructor.
     *
     * @param array $routes
     */
    public function __construct(array $routes)
    {
        $this->routes = $routes;
    }

    /**
     * Get a name of a page controller
     *
     * @param string $url
     * @param string $method
     * @return string
     * @throws Exception
     * @throws PageNotFound
     */
    public function getController(string $url, string $method) : string
    {
        if (empty($this->routes)) {
            throw new Exception('No routes!');
        }

        if (strpos($url, '?') !== false) {
            list($url,) = explode('?', $url);
        }

        foreach ($this->routes as $route) {
            if ($class = $this->_check($route, $method, $url)) {
                return $class;
            }
        }
        throw new PageNotFound('Page Not Found');
    }

    /**
     * Get vars from request uri
     *
     * @return array
     */
    public function getPathVars() : array
    {
        return $this->_pathVars;
    }

    /**
     * Check if the controller is exist
     *
     * @param array $route
     * @param string $method
     * @param string $url
     * @return string
     * @throws PageNotFound
     */
    private function _check(array $route, string $method, string $url) : string
    {
        if (!$this->_checkMethod($route, $method)) {
            return false;
        }

        if (empty($route['class'])) {
            throw new Exception('Controller is not specified');
        }

        if (!preg_match('/^' . strtr(trim($route['url']), ['/'  => '\/']) . '\/*$/iu', urldecode($url), $matches)) {
            return false;
        }

        if (!class_exists($route['class'])) {
            throw new PageNotFound('Controller "' . $route['class'] . '" doesn\'t exists!');
        }

        $this->_pathVars = array_merge($this->_pathVars, $matches);
        return $route['class'];
    }

    /**
     * Check if method is allow
     *
     * @param array $route
     * @return boolean
     */
    protected function _checkMethod(array $route, string $method) : bool
    {
        if (!array_key_exists('methods', $route)) {
            return true;
        }
        $methods = array_map('strtoupper', array_filter((array)$route['methods']));
        return in_array($method, $methods);
    }
}
