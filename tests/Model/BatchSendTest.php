<?php

namespace Linktracker\Tracking\Model;

use Linktracker\Tracking\Api\ConfigInterface as TrackingConfig;
use Linktracker\Tracking\Api\Data\TrackingInterface;
use Linktracker\Tracking\Api\Data\TrackingSearchResultInterface;
use Linktracker\Tracking\Api\TrackingRepositoryInterface;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\Api\SimpleBuilderInterface;
use PHPUnit\Framework\TestCase;

class BatchSendTest extends TestCase
{

    /**
     * @var BatchSend
     */
    private $batchSend;
    /**
     * @var SendInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $sendInterfaceMock;
    /**
     * @var TrackingRepositoryInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $trackingRepositoryMock;
    /**
     * @var SearchCriteriaBuilder|\PHPUnit\Framework\MockObject\MockObject
     */
    private $searchCriteriaBuilderMock;


    protected function setUp(): void
    {
        $this->sendInterfaceMock = $this->createMock(SendInterface::class);
        $this->trackingRepositoryMock = $this->createMock(TrackingRepositoryInterface::class);
        $this->searchCriteriaBuilderMock = $this->getMockBuilder(SearchCriteriaBuilder::class)
                ->disableOriginalConstructor()
                ->getMock();

        $this->batchSend = new BatchSend(
            $this->sendInterfaceMock,
            $this->trackingRepositoryMock,
            $this->searchCriteriaBuilderMock
        );
    }

    /**
     * Test execute functionality
     * Should do a search for tracking items
     * Should hand over these to the send interface
     * And should save the status
     */
    public function testExecute()
    {
        $batchSend = $this->batchSend;

        $searchCriteriaMock = $this->createMock(SearchCriteria::class);
        $trackingSearchResult = $this->createMock(TrackingSearchResultInterface::class);

        $searchCriteriaBuilderMock = $this->searchCriteriaBuilderMock;
        $repositoryMock = $this->trackingRepositoryMock;
        $sendMock = $this->sendInterfaceMock;

        $searchCriteriaBuilderMock->method('addFilter')
                ->willReturnSelf();

        $searchCriteriaBuilderMock->method('create')
                ->willReturn($searchCriteriaMock);

        $trackingItem = $this->createMock(TrackingInterface::class);

        // Should do a search
        $repositoryMock->expects($this->once())
                ->method('getList')
                ->with($searchCriteriaMock)
                ->willReturn($trackingSearchResult);

        // Should get items
        // Hand over 2 items
        $trackingSearchResult->expects($this->once())
            ->method('getItems')
            ->willReturn([$trackingItem, $trackingItem]);

        // Should hand over the information to the send interface
        $sendMock->expects($this->exactly(2))
                ->method('sendTrackingData')
                ->willReturn(true, false);

        // I would love to test the updateStatus, but this is already tested in updateStatus

        $batchSend->execute();
    }

    public function testUpdateStatus()
    {
        $batchSend = $this->batchSend;

        $repositoryMock = $this->trackingRepositoryMock;
        $trackingMock = $this->createMock(TrackingInterface::class);

        $trackingMock->expects($this->once())
                ->method('setStatus')
                ->with(TrackingConfig::STATUS_SEND);

        $repositoryMock->expects($this->once())
                ->method('save')
                ->with($trackingMock);

        $batchSend->updateStatus($trackingMock, TrackingConfig::STATUS_SEND);
    }

    public function testGetStatusFilter()
    {
        $batchSend = $this->batchSend;

        $searchCriteriaBuilderMock = $this->searchCriteriaBuilderMock;

        $searchCriteriaBuilderMock->expects($this->once())
                ->method('addFilter')
                ->with('status', TrackingConfig::STATUS_NEW)
                ->willReturnSelf();

        $searchCriteriaMock = $this->createMock(SearchCriteria::class);

        $searchCriteriaBuilderMock->expects($this->once())
                ->method('create')
                ->willReturn($searchCriteriaMock);


        $this->assertInstanceOf(SearchCriteria::class, $batchSend->getStatusFilter(TrackingConfig::STATUS_NEW));
    }
}
