<?php
namespace Shortener\Controller;

use Shortener\Data\UrlRegestryException;
use Shortener\Data\UrlRegistry;
use Shortener\Exception\InternalException;

/**
 * Redirect Page Controller
 *
 * @package Shortener\Controller
 */
class Redirect extends AbstractController
{
    /**
     * Redirect to a full url
     *
     * @throws InternalException
     */
    public function run()
    {
        try {
            $fullUrl = (new UrlRegistry($this->db))->getFullUrl($this->getPathVar('shortUrl'));
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
