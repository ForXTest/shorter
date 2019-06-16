<?php
namespace Shortener\Controller;

use Shortener\Data\UrlRegestryException;
use Shortener\Data\UrlRegistry;
use Shortener\Exception\InternalException;
use Shortener\LoggerInterface;
use Shortener\Request;
use Shortener\View;

/**
 * Redirect Page Controller
 *
 * @package Shortener\Controller
 */
class Redirect extends AbstractController
{
    /**
     * @var UrlRegistry
     */
    protected $urlRegistry;


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
     * Redirect to a full url
     *
     * @throws InternalException
     */
    public function run(): void
    {
        try {
            $fullUrl = $this->urlRegistry->getFullUrl($this->getPathVar('shortUrl'));
        } catch (UrlRegestryException $e) {
            $this->logger->logException($e);
            throw new InternalException('Database Error', $e->getCode(), $e);
        }

        if (empty($fullUrl)) {
            $this->response->setRedirect('/');
            return;
        }

        $this->response->setRedirect($fullUrl);
    }
}
