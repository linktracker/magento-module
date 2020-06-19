<?php

namespace Linktracker\Tracking\Observer;

use Linktracker\Tracking\Api\ConfigInterface;
use Linktracker\Tracking\Model\CookieInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Request\Http;
use Magento\Framework\App\Response\HttpInterface;
use Magento\Framework\Event;
use Magento\Framework\Event\Observer;
use PHPUnit\Framework\TestCase;

class RedirectTest extends TestCase
{
    /**
     * @var CookieInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $cookieMock;
    /**
     * @var Observer|\PHPUnit\Framework\MockObject\MockObject
     */
    private $observerMock;
    /**
     * @var Redirect
     */
    private $redirect;
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $requestMock;
    /**
     * @var \PHPUnit\Framework\MockObject\MockObject
     */
    private $eventMock;
    /**
     * @var HttpInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $responseMock;

    protected function setUp(): void
    {
        $this->cookieMock = $this->createMock(CookieInterface::class);
        $this->responseMock = $this->createMock(HttpInterface::class);

        $this->observerMock = $this->getMockBuilder(Observer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->requestMock = $this->getMockBuilder(Http::class)
                ->disableOriginalConstructor()
                ->getMock();

        $this->eventMock = $this->getMockBuilder(Event::class)
            ->disableOriginalConstructor()
            ->getMock();

        // Link common objects
        $this->observerMock->method('getEvent')
                ->willReturn($this->eventMock);

        $this->redirect = new Redirect(
            $this->cookieMock
        );
    }

    public function testSetRedirect()
    {
        $redirect = $this->redirect;

        $requestMock = $this->requestMock;
        $responseMock = $this->responseMock;

        $requestMock->expects($this->once())
                ->method('getOriginalPathInfo')
                ->willReturn('/something?' . ConfigInterface::TRACKING_REQUEST_PARAMETER . '=12345');

        $responseMock->expects($this->once())
                ->method('setRedirect')
                ->with('/something', 301);

        $responseMock->expects($this->once())
                ->method('sendResponse');

        $redirect->setRedirect($requestMock, $responseMock);
    }

    public function testExecuteWithValue()
    {
        $redirect = $this->redirect;
        $cookieMock = $this->cookieMock;

        $observerMock = $this->observerMock;
        $eventMock = $this->eventMock;

        $requestMock = $this->requestMock;
        $responseMock = $this->responseMock;

        $controllerActionMock = $this->getMockForAbstractClass(
            Action::class,
            [],
            '',
            false,
            true,
            true,
            ['getResponse']
        );

        $controllerActionMock->expects($this->once())
                ->method('getResponse')
                ->willReturn($responseMock);

        $requestMock->expects($this->once())
                ->method('isGet')
                ->willReturn(true);

        $requestMock->expects($this->once())
                ->method('getParam')
                ->with(ConfigInterface::TRACKING_REQUEST_PARAMETER)
                ->willReturn('12345');

        $eventMock->expects($this->exactly(2))
                ->method('getData')
                ->withConsecutive(['request'], ['controller_action'])
                ->willReturn($requestMock, $controllerActionMock);


        $cookieMock->expects($this->once())
                ->method('setValue')
                ->with('12345');

        $redirect->execute($observerMock);

        return $redirect;
    }

    /**
     * @depends testExecuteWithValue
     *
     * @param Redirect $redirect
     */
    public function testExecuteTwiceIsIgnored(Redirect $redirect)
    {
        $observerMock = $this->observerMock;

        $observerMock->expects($this->never())
                ->method('getEvent');

        $redirect->execute($observerMock);
    }

    public function testExecuteNotGet()
    {
        $redirect = $this->redirect;
        $cookieMock = $this->cookieMock;

        $observerMock = $this->observerMock;
        $eventMock = $this->eventMock;
        $requestMock = $this->requestMock;

        $eventMock->method('getData')
                ->with('request')
                ->willReturn($requestMock);

        $requestMock->expects($this->once())
                ->method('isGet')
                ->willReturn(false);

        $requestMock->expects($this->never())
                ->method('getParam');

        $cookieMock->expects($this->never())
                ->method('setValue');

        $redirect->execute($observerMock);
    }

    public function testExecuteNoGetParameter()
    {
        $redirect = $this->redirect;
        $cookieMock = $this->cookieMock;

        $observerMock = $this->observerMock;
        $eventMock = $this->eventMock;
        $requestMock = $this->requestMock;

        $eventMock->method('getData')
            ->with('request')
            ->willReturn($requestMock);

        $requestMock->method('isGet')
            ->willReturn(true);

        $requestMock->expects($this->once())
            ->method('getParam')
            ->willReturn(null);

        $cookieMock->expects($this->never())
            ->method('setValue');

        $redirect->execute($observerMock);
    }

    public function dataProviderForRemoveLinks(): array
    {
        return [
            ['/something?' . ConfigInterface::TRACKING_REQUEST_PARAMETER . '=12345', '/something'],
            ['/something?other=something&' . ConfigInterface::TRACKING_REQUEST_PARAMETER . '=12345', '/something?other=something'],
            ['/something?' . ConfigInterface::TRACKING_REQUEST_PARAMETER . '=12345&other=something', '/something?other=something'],
            ['/something?param1=value1&' . ConfigInterface::TRACKING_REQUEST_PARAMETER . '=12345&other=something', '/something?param1=value1&other=something'],
        ];
    }

    /**
     * @dataProvider dataProviderForRemoveLinks
     *
     * @param string $input
     * @param string $expected
     */
    public function testRemoveLinkTrackerParam(string $input, string $expected)
    {
        $redirect = $this->redirect;
        $this->assertSame($expected, $redirect->removeLinkTrackerParam($input));
    }

}
