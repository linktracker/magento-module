<?php

namespace Linktracker\Tracking\Cron;

use Linktracker\Tracking\Model\BatchSendInterface;
use Psr\Log\LoggerInterface;

class Send
{
    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var BatchSendInterface
     */
    protected $batchSend;

    /**
     * Send constructor.
     * @param BatchSendInterface $batchSend
     * @param LoggerInterface $logger
     */
    public function __construct(
        BatchSendInterface $batchSend,
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
