<?php

declare(strict_types=1);

use Shortener\Data\UrlRegestryException;

class RedirectTest extends \PHPUnit\Framework\TestCase
{
    /** @var \Shortener\Controller\Redirect */
    protected $controller;

    /**
     * @return void
     */
    public function setUp(): void
    {
        $requestMock = $this->getMockBuilder('\Shortener\Request')
            ->setMethods([])
            ->getMock();

        $smartyMock = $this->getMockBuilder('\Shortener\View')
            ->disableOriginalConstructor()
            ->setMethods(['checkIsWritableCompileDir'])
            ->getMock();

        $smartyMock->method('checkIsWritableCompileDir')->willReturn(true);

        $logMock = $this->getMockBuilder('\Shortener\Logger')
            ->disableOriginalConstructor()
            ->setMethods([])
            ->getMock();

        $registryMock = $this->getMockBuilder('\Shortener\Data\UrlRegistry')
            ->disableOriginalConstructor()
            ->setMethods(['getFullUrl'])
            ->getMock();

        $map = require ROOT_PATH . 'tests/unit/fixtures/urlRegistryData.php';

        $registryMock->method('getFullUrl')->will($this->returnCallback(
            function($arg) use ($map) {

                if (isset($map[$arg])) {
                    return $map[$arg];
                }

                throw new UrlRegestryException('Test');
            }
        ));

        $this->controller = new \Shortener\Controller\Redirect(
            $requestMock,
            $smartyMock,
            $logMock,
            $registryMock
        );
    }

    /**
     * @dataProvider redirectData
     */
    public function testRedirect(string $shortUrl, string $redirectUrl): void
    {
        $this->controller->setPathVars(['shortUrl' => $shortUrl]);
        $this->controller->run();
        $response = $this->controller->getResponse();

        $this->assertEquals(\Shortener\Response::STATUS_MOVED_PERMANENTLY, $response->getHttpCode());
        $this->assertEquals('', $response->getBody());
        $this->assertEquals(['Location' => ['name' => 'Location', 'value' => $redirectUrl]], $response->getHeaders());
    }

    public function redirectData(): array
    {
        return [
            [
                'shortUrl' => 'jnsfsfd',
                'redirectUrl' => 'http://youtube.net/llalala',
            ],
            [
                'shortUrl' => 'alalala',
                'redirectUrl' => 'http://google.com/akfmalks',
            ],
            [
                'shortUrl' => 'qwerty',
                'redirectUrl' => '/',
            ],
        ];
    }

    /**
     * @expectedException \Shortener\Exception\InternalException
     */
    public function testException(): void
    {
        $this->controller->setPathVars(['shortUrl' => 'fabfbkaf']);
        $this->controller->run();
    }
}
