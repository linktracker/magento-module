<?php

namespace Linktracker\Tracking\Model;

use Linktracker\Tracking\Api\ConfigInterface as TrackingConfig;
use Linktracker\Tracking\Api\Data\TrackingInterface;
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
    private $trackingReposityryMock;
    /**
     * @var SearchCriteriaBuilder|\PHPUnit\Framework\MockObject\MockObject
     */
    private $searchCriteriaMock;


    protected function setUp(): void
    {
        $this->sendInterfaceMock = $this->createMock(SendInterface::class);
        $this->trackingReposityryMock = $this->createMock(TrackingRepositoryInterface::class);
        $this->searchCriteriaMock = $this->getMockBuilder(SearchCriteriaBuilder::class)
                ->disableOriginalConstructor()
                ->getMock();

        $this->batchSend = new BatchSend(
            $this->sendInterfaceMock,
            $this->trackingReposityryMock,
            $this->searchCriteriaMock
        );
    }


    public function testUpdateStatus()
    {
        $batchSend = $this->batchSend;

        $repositoryMock = $this->trackingReposityryMock;
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

        $searchCriteriaBuilderMock = $this->searchCriteriaMock;

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

    public function testExecute()
    {

    }
}
