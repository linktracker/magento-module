<?php

namespace Linktracker\Tracking\Model;

use Linktracker\Tracking\Api\ConfigInterface as TrackingConfig;
use Linktracker\Tracking\Api\Data\TrackingInterface;
use Linktracker\Tracking\Api\TrackingClientInterface;

class Send implements SendInterface
{
    /**
     * @var ConfigInterface
     */
    private $config;
    /**
     * @var TrackingClientInterface
     */
    private $trackingClient;

    public function __construct(
        ConfigInterface $config,
        TrackingClientInterface $trackingClient
    ) {
        $this->config = $config;
        $this->trackingClient = $trackingClient;
    }

    public function sendTrackingData(TrackingInterface $tracking): bool
    {
        return $this->trackingClient->send($this->config->getTrackingUrl($tracking->getStoreId()), [
                TrackingConfig::API_PARAM_ID => $tracking->getTrackingId(),
                TrackingConfig::API_PARAM_ORDER_ID => $tracking->getOrderIncrementId(),
                TrackingConfig::API_PARAM_AMOUNT => $tracking->getGrandTotal(),
                TrackingConfig::API_PARAM_TIMESTAMP => $this->convertCreatedAtToTimestamp($tracking->getCreatedAt()),
            ]
        );
    }

    public function convertCreatedAtToTimestamp(string $timestamp): string
    {
        $datetime = new \DateTimeImmutable($timestamp, new \DateTimeZone('UTC'));
        $timezone = new \DateTimeZone(TrackingConfig::API_TIMESTAMP_TIMEZONE);

        return $datetime->setTimezone($timezone)
                ->format(TrackingConfig::API_TIMESTAMP_FORMAT);

    }

}
