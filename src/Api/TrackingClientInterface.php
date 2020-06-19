<?php

namespace Linktracker\Tracking\Api;

interface TrackingClientInterface
{
    public function send(string $url, array $data, int $timeout = ConfigInterface::API_CONNECTION_TIMEOUT): bool;
}
