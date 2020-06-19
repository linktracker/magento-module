<?php

namespace Linktracker\Tracking\Model;

use Linktracker\Tracking\Api\ConfigInterface as TrackingConfig;
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

    public function sendTrackingData(string $trackingCode, string $incrementId, float $orderAmount, int $storeId): bool
    {
        return $this->trackingClient->send($this->config->getTrackingUrl($storeId), [
                TrackingConfig::API_PARAM_ID => $trackingCode,
                TrackingConfig::API_PARAM_ORDER_ID => $incrementId,
                TrackingConfig::API_PARAM_AMOUNT => $orderAmount
            ]
        );
    }
}
