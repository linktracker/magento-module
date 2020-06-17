<?php

namespace Linktracker\Tracking\Cron;

use Psr\Log\LoggerInterface;

class Send
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var \Linktracker\Tracking\Model\Info
     */
    protected $info;

    /**
     * Send constructor.
     * @param \Linktracker\Tracking\Model\Info $info
     * @param LoggerInterface $logger
     */
    public function __construct
    (
        \Linktracker\Tracking\Model\Info $info,
        LoggerInterface $logger
    ) {
        $this->info = $info;
        $this->logger = $logger;
    }

    public function execute()
    {
        $this->logger->info('Start sending tracking information using cron');

        $this->info->execute();

        $this->logger->info('Finished sending tracking information using cron');
    }

}
