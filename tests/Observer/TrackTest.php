<?php

namespace Linktracker\Tracking\Observer;

use Linktracker\Tracking\Api\TrackingRepositoryInterface;
use Linktracker\Tracking\Model\CookieInterface;
use Magento\Framework\Event;
use Magento\Framework\Event\Observer;
use Magento\Sales\Api\Data\OrderInterface;
use PHPUnit\Framework\TestCase;

class TrackTest extends TestCase
{

    /**
     * @var TrackingRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $repositoryMock;
    /**
     * @var CookieInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $cookieMock;
    /**
     * @var Observer|\PHPUnit\Framework\MockObject\MockObject
     */
    private $observerMock;
    /**
     * @var Track
     */
    private $track;

    protected function setUp(): void
    {
        $this->repositoryMock = $this->createMock(TrackingRepositoryInterface::class);
        $this->cookieMock = $this->createMock(CookieInterface::class);

        $this->observerMock = $this->getMockBuilder(Observer::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->track = new Track(
            $this->repositoryMock,
            $this->cookieMock
        );
    }

    public function testExecute()
    {
        $repositoryMock = $this->repositoryMock;
        $cookieMock = $this->cookieMock;
        $observerMock = $this->observerMock;

        $track = $this->track;

        $cookieMock->expects($this->once())
                ->method('exists')
                ->willReturn(true);

        $cookieMock->expects($this->once())
                ->method('getValue')
                ->willReturn('1234');

        $orderMock = $this->createMock(OrderInterface::class);

        $orderMock->expects($this->once())
                ->method('getEntityId')
                ->willReturn(1);

        $orderMock->expects($this->once())
                ->method('getIncrementId')
                ->willReturn('1000');

        $orderMock->expects($this->once())
                ->method('getGrandTotal')
                ->willReturn(10.00);

        $repositoryMock->expects($this->once())
                ->method('createTracking')
                ->with('1234', 1, '1000', 10.00);

        $eventMock = $this->getMockBuilder(Event::class)
                ->disableOriginalConstructor()
                ->getMock();

        $eventMock->expects($this->once())
                ->method('getData')
                ->with('order')
                ->willReturn($orderMock);

        $observerMock->expects($this->once())
                ->method('getEvent')
                ->willReturn($eventMock);

        $track->execute($observerMock);
    }

    public function testExecuteNoCookie()
    {
        $cookieMock = $this->cookieMock;
        $observerMock = $this->observerMock;

        $track = $this->track;

        $cookieMock->expects($this->once())
            ->method('exists')
            ->willReturn(false);

        $cookieMock->expects($this->never())
            ->method('getValue');

        $track->execute($observerMock);
    }
}
