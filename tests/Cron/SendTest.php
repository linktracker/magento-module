<?php

namespace Linktracker\Tracking\Cron;

use Linktracker\Tracking\Model\BatchSendInterface;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

class SendTest extends TestCase
{

    public function testExecute()
    {
        $batchSendMock = $this->createMock(BatchSendInterface::class);
        $loggerMock = $this->createMock(LoggerInterface::class);

        $cronSend = new Send(
            $batchSendMock,
            $loggerMock
        );

        $batchSendMock->expects($this->once())
                ->method('execute');

        $cronSend->execute();
    }
}
