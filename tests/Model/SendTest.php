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
            [true, '12345', '100000123', 12.00, 1, '2020-09-23 13:00:00'],
            [false, '12345', '100000124', 10.00, 0, '2020-09-23 13:04:01']
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
     * @param string $timestampUTC
     */
    public function testSendTrackingData(bool $result, string $trackingCode, string $incrementId, float $orderAmount, int $storeId, string $timestampUTC)
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
        $trackingMock->expects($this->once())
                ->method('getCreatedAt')
                ->willReturn($timestampUTC);

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
                        TrackingConfig::API_PARAM_AMOUNT => $orderAmount,
                        TrackingConfig::API_PARAM_TIMESTAMP => $this->formatTimestamp($timestampUTC)
                    ]
                )
                ->willReturn($result);

        $send = new Send(
            $configMock,
            $trackingClientMock
        );

        $this->assertSame($result, $send->sendTrackingData($trackingMock));
    }

    public function testConvertCreatedAtToTimestamp()
    {
        $configMock = $this->configMock;
        $trackingClientMock = $this->trackingClientMock;

        $send = new Send(
            $configMock,
            $trackingClientMock
        );

        $timestamp = '2020-09-23 14:12:15';
//        $this->assertSame('20200923.161215', $send->convertCreatedAtToTimestamp($timestamp)); // summertime
//        $this->assertSame('20200923.151215', $send->convertCreatedAtToTimestamp($timestamp)); // wintertime
        $this->assertSame($this->formatTimestamp($timestamp), $send->convertCreatedAtToTimestamp($timestamp));
    }

    /**
     * We need to do this, because Magento safes in UTC, summertime is +2 and wintertime +1, I don't want to builtin summer/wintertime logic(because it isn't saved information)
     *
     * @param string $timestamp
     */
    private function formatTimestamp(string $timestamp)
    {
        $datetime = new \DateTimeImmutable($timestamp, new \DateTimeZone('UTC'));
        return $datetime->setTimezone(new \DateTimeZone(TrackingConfig::API_TIMESTAMP_TIMEZONE))
                ->format('Ymd.His');
    }


}
