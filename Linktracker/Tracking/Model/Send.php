<?php

namespace Linktracker\Tracking\Model;

use Linktracker\Tracking\Api\Config as StatusConfig;

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

    public function sendTrackingData(array $items): void {

    }

    public function getTrakingUrl(): string {
        return (string)$this->config->getGeneralConfigValue('tracking_url');
    }
}
