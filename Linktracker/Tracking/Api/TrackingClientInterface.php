<?php

namespace Linktracker\Tracking\Api;

interface TrackingClientInterface
{
    public function send(string $url, array $data): bool;
}
