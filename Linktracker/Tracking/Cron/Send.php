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
     * @var \Linktracker\Tracking\Model\BatchSend
     */
    protected $batchSend;

    /**
     * Send constructor.
     * @param \Linktracker\Tracking\Model\BatchSend $batchSend
     * @param LoggerInterface $logger
     */
    public function __construct
    (
        \Linktracker\Tracking\Model\BatchSend $batchSend,
        LoggerInterface $logger
    ) {
        $this->batchSend = $batchSend;
        $this->logger = $logger;
    }

    public function execute()
    {
        $this->logger->info('Start sending tracking information using cron');

        $this->batchSend->execute();

        $this->logger->info('Finished sending tracking information using cron');
    }

}
