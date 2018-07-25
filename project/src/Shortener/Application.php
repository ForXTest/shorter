<?php
namespace Shortener;
use Shortener\Router\PageNotFound;

/**
 * Application
 *
 * @package Shortener
 */
class Application
{
    /**
     * @var Container
     */
    private $di;

    /**
     * Application constructor.
     *
     * @param array $config
     */
    public function __construct(Container $di)
    {
        $this->di = $di;
    }

    /**
     * Run controllers
     */
    public function run()
    {
        try {
            $controller = ControllerFactory::create(
                $this->di['db'],
                $this->di['router'],
                $this->di['request'],
                $this->di['view']
            );
            $controller->run();
            return $controller->getResponse();

        } catch (PageNotFound $e) {
            header('HTTP/1.1  404 Not Found', true, 404);
            return;

        } catch (\Exception $e) {
            trigger_error($e);
            header('HTTP/1.1  500 Internal Server Error', true, 500);
            return;
        }
    }
}
