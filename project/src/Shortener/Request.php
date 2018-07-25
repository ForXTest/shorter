<?php
namespace Shortener;

/**
 * Class Request
 *
 * @package Shortener
 */
class Request
{
    /**
     * Get a host with a request scheme
     *
     * @return string
     */
    public function getHost()
    {
        return $this->getServer('REQUEST_SCHEME') . '://' . $this->getServer('HTTP_HOST') . '/';
    }
    /**
     * Get value from $_GET by $key
     *
     * @param string|int $key
     * @param mixed $default
     * @return mixed
     */
    public function getGet($key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    /**
     * Get value from $_POST by $key
     *
     * @param string|int $key
     * @param mixed $default
     * @return mixed
     */
    public function getPost($key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }

    /**
     * Return REQUEST_URI from $_SERVER
     *
     * @return string
     */
    public function getUrl() : string
    {
        return $this->getServer('REQUEST_URI', '');
    }


    /**
     * Return REQUEST_METHOD from $_SERVER
     *
     * @return string
     */
    public function getMethod() : string
    {
        return strtoupper($this->getServer('REQUEST_METHOD', ''));
    }

    /**
     * Get value from $_SERVER by $key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getServer(string $key, $default = null)
    {
        return $_SERVER[$key] ?? $default;
    }
}
