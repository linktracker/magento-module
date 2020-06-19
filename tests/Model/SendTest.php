<?php

namespace Linktracker\Tracking\Model;

use Linktracker\Tracking\Api\ConfigInterface as TrackingConfig;
use Linktracker\Tracking\Api\TrackingClientInterface;
use PHPUnit\Framework\TestCase;

class SendTest extends TestCase
{

    /**
     * @var string
     */
    private $url;

    protected function setUp(): void
    {
        $this->url = 'https://httpbin.org/get';
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
        $configMock = $this->createMock(ConfigInterface::class);
        $trackingClientMock = $this->createMock(TrackingClientInterface::class);

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

        $this->assertSame($result, $send->sendTrackingData($trackingCode, $incrementId, $orderAmount, $storeId));
    }
}
