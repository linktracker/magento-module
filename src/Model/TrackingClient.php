<?php

namespace Linktracker\Tracking\Model;

use Linktracker\Tracking\Api\ConfigInterface as TrackingConfig;
use Linktracker\Tracking\Api\TrackingClientInterface;
use Psr\Log\LoggerInterface;

class TrackingClient implements TrackingClientInterface
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }

    public function send(string $url, array $data, int $timeout = TrackingConfig::API_CONNECTION_TIMEOUT): bool
    {
        try {
            $ctx = stream_context_create([
                    'http'=>
                        [
                            'timeout' => $timeout
                        ]
                ]
            );

            $urlString = $url . '?' . http_build_query($data);
            $result = file_get_contents($urlString, false, $ctx) === false ? false : true;

            $this->logger->debug(sprintf('Send message to "%s" with result %s', $urlString, (string)$result));

            return $result;
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
        }

        return false;
    }
}
