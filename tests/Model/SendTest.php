<?php

namespace Linktracker\Tracking\Model;

use Linktracker\Tracking\Api\ConfigInterface as TrackingConfig;
use Linktracker\Tracking\Api\Data\TrackingInterface;
use Linktracker\Tracking\Api\TrackingClientInterface;
use PHPUnit\Framework\TestCase;

class SendTest extends TestCase
{

    /**
     * @var string
     */
    private $url;
    /**
     * @var TrackingInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $trackingMock;
    /**
     * @var ConfigInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $configMock;
    /**
     * @var TrackingClientInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $trackingClientMock;

    protected function setUp(): void
    {
        $this->url = 'https://httpbin.org/get';
        $this->trackingMock = $this->createMock(TrackingInterface::class);

        $this->configMock = $this->createMock(ConfigInterface::class);
        $this->trackingClientMock = $this->createMock(TrackingClientInterface::class);

    }

    public function dataProviderSendTrackingData(): array
    {
        return [
            [true, '12345', '100000123', 12.00, 1],
            [false, '12345', '100000124', 10.00, 0]
        ];
    }

    /**
     * @dataProvider dataProviderSendTrackingData
     *
     * @param bool $result
     * @param string $trackingCode
     * @param string $incrementId
     * @param float $orderAmount
     * @param int $storeId
     */
    public function testSendTrackingData(bool $result, string $trackingCode, string $incrementId, float $orderAmount, int $storeId)
    {
        $configMock = $this->configMock;
        $trackingClientMock = $this->trackingClientMock;
        $trackingMock = $this->trackingMock;

        $trackingMock->expects($this->once())
                ->method('getTrackingId')
                ->willReturn($trackingCode);
        $trackingMock->expects($this->once())
                ->method('getOrderIncrementId')
                ->willReturn($incrementId);
        $trackingMock->expects($this->once())
                ->method('getGrandTotal')
                ->willReturn($orderAmount);
        $trackingMock->expects($this->once())
                ->method('getStoreId')
                ->willReturn($storeId);

        $configMock->expects($this->once())
                ->method('getTrackingUrl')
                ->with($storeId)
                ->willReturn($this->url);

        $trackingClientMock->expects($this->once())
                ->method('send')
                ->with(
                    $this->url,
                    [
                        TrackingConfig::API_PARAM_ID => $trackingCode,
                        TrackingConfig::API_PARAM_ORDER_ID => $incrementId,
                        TrackingConfig::API_PARAM_AMOUNT => $orderAmount
                    ]
                )
                ->willReturn($result);

        $send = new Send(
            $configMock,
            $trackingClientMock
        );

        $this->assertSame($result, $send->sendTrackingData($trackingMock));
    }
    }
}
