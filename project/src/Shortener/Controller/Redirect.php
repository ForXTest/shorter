<?php
namespace Shortener\Controller;

use Shortener\Data\UrlRegestryException;
use Shortener\Data\UrlRegistry;
use Shortener\Exception\InternalException;
use Shortener\Request;

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
     * Redirect to a full url
     *
     * @throws InternalException
     */
    public function run()
    {
        try {
            $fullUrl = $this->urlRegistry->getFullUrl($this->getPathVar('shortUrl'));
        } catch (UrlRegestryException $e) {
            throw new InternalException('Database Error', 0, $e);
        }

        if (empty($fullUrl)) {
            $this->response->setRedirect('/');
            return;
        }
        $this->response->setRedirect($fullUrl);
    }
}
