<?php

namespace Linktracker\Tracking\Model;

class Client implements \Linktracker\Tracking\Api\TrackingClientInterface
{
    const TIME_OUT = 20; //seconds

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    public function __construct(
        \Psr\Log\LoggerInterface $logger
    )
    {
        $this->logger = $logger;
    }

    public function send(string $url, array $data): bool
    {
        $ctx = stream_context_create(array('http'=>
            array(
                'timeout' => self::TIME_OUT
            )
        ));

        try {
            $urlString = $url . '?' . http_build_query($data);
            return file_get_contents($urlString, false, $ctx) === false ? false : true;
        } catch (\Exception $exception) {
            $this->logger->error($exception->getMessage());
            return false;
        }
    }
}
