<?php
namespace Shortener\Controller;

use Shortener\Data\UrlRegestryException;
use Shortener\Data\UrlRegistry;
use Shortener\Request;

/**
 * Main Page Controller
 *
 * @package Shortener\Controller
 */
class Index extends AbstractController
{
    /**
     * @var UrlRegistry
     */
    protected $urlRegistry;

    /**
     * @var int
     */
    const LENGTH = 10;

    /**
     * @var string
     */
    const ALLOW_SYMBOLS = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';


    /**
     * Constructor
     *
     * @param \PDO $db
     * @param Request $request
     * @param \Smarty $view
     * @throws \Exception
     */
    public function __construct(Request $request, \Smarty $view, UrlRegistry $urlRegistry)
    {
        parent::__construct($request, $view);

        $this->urlRegistry = $urlRegistry;
    }

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

        try {
            $shortUrl = $this->urlRegistry->checkUrl($url);
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
            $this->urlRegistry->setUrl($url, $shortUrl);
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
