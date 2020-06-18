<?php

namespace Linktracker\Tracking\Model;

use Linktracker\Tracking\Api\Config as TrackingConfig;

class Send
{
    /**
     * @var \Linktracker\Tracking\Model\Config
     */
    protected $config;

    /**
     * @var \Linktracker\Tracking\Api\TrackingClientInterface
     */
    protected $trackingClient;

    public function __construct(
        \Linktracker\Tracking\Model\Config $config,
        \Linktracker\Tracking\Api\TrackingClientInterface $trackingClient
    ) {
        $this->config = $config;
        $this->trackingClient = $trackingClient;
    }

    public function sendTrackingData(string $trackerId, string $orderId, float $orderAmount): bool
    {
        return $this->trackingClient->send($this->getTrakingUrl(), [
                TrackingConfig::TRACKING_REQUEST_PARAMETER => $trackerId,
                'order_id' => $orderId,
                'order_amount' => $orderAmount
            ]
        );
    }

    public function getTrakingUrl(): string
    {
        return (string)$this->config->getGeneralConfigValue('tracking_url');
    }
}
