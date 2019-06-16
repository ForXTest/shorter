<?php

declare(strict_types=1);

class RouterTest extends \PHPUnit\Framework\TestCase
{
    protected $routes = [];

    /**
     * @return void
     */
    public function setUp(): void
    {
        $this->routes = [
            [
                'url' => '/(?<shortUrl>[^?]+)',
                'class' => '\Shortener\Controller\Redirect',
                'methods' => ['GET']
            ],
            [
                'url' => '/',
                'class' => '\Shortener\Controller\Index',
                'methods' => ['GET', 'POST']
            ]
        ];
    }

    /**
     * @dataProvider routesData
     */
    public function testRoute(string $requestUri, string $method, string $expectedController)
    {
        $router = new \Shortener\Router($this->routes);
        $class = $router->getController($requestUri, $method);

        $this->assertEquals($expectedController, $class);
    }

    public function testPathVars()
    {
        $router = new \Shortener\Router($this->routes);
        $class = $router->getController('/gfgmjv/', 'GET');
        $pathVars = $router->getPathVars();

        $this->assertFalse(empty($pathVars['shortUrl']));
        $this->assertEquals('gfgmjv', $pathVars['shortUrl']);
    }

    /**
     * @dataProvider routesData
     * @expectedException Shortener\Router\Exception
     */
    public function testEmptyRoutes(string $requestUri, string $method)
    {
        $router = new \Shortener\Router([]);
        $router->getController($requestUri, $method);
    }

    /**
     * @dataProvider routesData
     * @expectedException Shortener\Router\Exception
     */
    public function testNotSpecifiedController(string $requestUri, string $method)
    {
        $routes = [
            [
                'url' => '/(?<shortUrl>[^?]+)',
            ]
        ];

        $router = new \Shortener\Router($routes);
        $router->getController($requestUri, $method);

        $this->expectExceptionMessage('Controller is not specified');
    }

    /**
     * @dataProvider routesData
     * @expectedException Shortener\Router\Exception
     */
    public function testNotSpecifiedRoute(string $requestUri, string $method)
    {
        $routes = [
            [
                'class' => '\Shortener\Controller\Index',
            ]
        ];

        $router = new \Shortener\Router($routes);
        $router->getController($requestUri, $method);

        $this->expectExceptionMessage('Controller is not specified');
    }

    /**
     * @dataProvider routesData
     * @expectedException Shortener\Router\PageNotFound
     */
    public function testNotFountRoute(string $requestUri, string $method)
    {
        $routes = [
            [
                'url' => '/(?<shortUrl>[^?]+)',
                'class' => '\Shortener\Controller\Delete',
                'methods' => ['DELETE']
            ]
        ];
        $router = new \Shortener\Router($routes);
        $router->getController($requestUri, $method);
    }

    public function routesData(): array
    {
        return [
            [
                'requestUri' => '/shortUrl/',
                'method' => 'GET',
                'expectedController' => '\Shortener\Controller\Redirect'
            ],
            [
                'requestUri' => '/',
                'method' => 'GET',
                'expectedController' => '\Shortener\Controller\Index'
            ],
            [
                'requestUri' => '/',
                'method' => 'POST',
                'expectedController' => '\Shortener\Controller\Index'
            ],
        ];
    }
}
