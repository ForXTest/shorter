<?php
namespace Shortener\Controller;

use Shortener\Data\UrlRegestryException;
use Shortener\Data\UrlRegistry;

/**
 * Main Page Controller
 *
 * @package Shortener\Controller
 */
class Index extends AbstractController
{
    /**
     * @var int
     */
    const LENGTH = 10;

    /**
     * @var string
     */
    const ALLOW_SYMBOLS = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * Run this controller
     */
    public function run()
    {
        $method = $this->request->getMethod();

        if ($method == 'POST') {
            return $this->ajax();
        }

        $this->response->setBody($this->view->fetch('index.tpl'));
    }

    /**
     * Xhr request processing
     */
    private function ajax()
    {
        $url = trim($this->request->getPost('url'));

        $registry = new UrlRegistry($this->db);

        try {
            $shortUrl = $registry->checkUrl($url);
        } catch (UrlRegestryException $e) {
            $this->logException($e);
            $this->setAjaxRespose('', 'Internal error!', 500);
            return;
        }

        if (!empty($shortUrl)) {
            $this->setAjaxRespose($this->request->getHost() . $shortUrl);
            return;
        }

        $shortUrl = $this->makeShortUrl();

        try {
            $registry->setUrl($url, $shortUrl);
        } catch (UrlRegestryException $e) {
            $this->logException($e);
            $this->setAjaxRespose('', 'Internal error!', 500);
            return;
        }

        $this->setAjaxRespose($this->request->getHost() . $shortUrl);
    }

    /**
     * Make a random short url
     *
     * @return string
     */
    private function makeShortUrl() : string
    {
        return substr(
            str_shuffle(str_repeat(self::ALLOW_SYMBOLS, ceil(self::LENGTH/strlen(self::ALLOW_SYMBOLS)))),
            1,
            self::LENGTH
        );
    }

    /**
     * Set Xhr response
     *
     * @param string $result
     * @param string $error
     * @param int $code
     */
    private function setAjaxRespose($result = '', $error = '', $code = 200)
    {
        $this->response->setHttpCode($code);
        $this->response->setBody(['result' => $result, 'error' => $error]);
    }
}
