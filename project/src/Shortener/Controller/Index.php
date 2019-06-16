<?php

namespace Shortener\Controller;

use Shortener\Data\UrlRegestryException;
use Shortener\Data\UrlRegistry;
use Shortener\LoggerInterface;
use Shortener\Request;
use Shortener\Response;
use Shortener\View;

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
    private const LENGTH = 10;

    /**
     * @var string
     */
    private const ALLOW_SYMBOLS = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

    /**
     * @param Request $request
     * @param View $view
     * @param LoggerInterface $logger
     * @param UrlRegistry $urlRegistry
     * @throws \Exception
     */
    public function __construct(
        Request $request,
        View $view,
        LoggerInterface $logger,
        UrlRegistry $urlRegistry
    ) {
        parent::__construct($request, $view, $logger);

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
    private function ajax(): void
    {
        $url = trim($this->request->getPost('url'));

        try {
            $shortUrl = $this->urlRegistry->checkUrl($url);
        } catch (UrlRegestryException $e) {
            $this->logger->logException($e);
            $this->setAjaxRespose('', 'Internal error!', Response::STATUS_INTERNAL_ERROR);
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
            $this->logger->logException($e);
            $this->setAjaxRespose('', 'Internal error!', Response::STATUS_INTERNAL_ERROR);
            return;
        }

        $this->setAjaxRespose($this->request->getHost() . $shortUrl);
    }

    /**
     * Make a random short url
     *
     * @return string
     */
    private function makeShortUrl(): string
    {
        return substr(
            str_shuffle(
                str_repeat(
                    self::ALLOW_SYMBOLS,
                    ceil(self::LENGTH/strlen(self::ALLOW_SYMBOLS))
                )
            ),
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
    private function setAjaxRespose($result = '', $error = '', $code = Response::STATUS_OK): void
    {
        $this->response->setHttpCode($code);
        $this->response->setBody(['result' => $result, 'error' => $error]);
    }
}
