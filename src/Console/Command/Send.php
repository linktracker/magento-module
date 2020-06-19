<?php

namespace Linktracker\Tracking\Console\Command;

use Linktracker\Tracking\Model\BatchSendInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Send
 * @codeCoverageIgnore
 */
class Send extends Command
{
    /**
     * @var BatchSendInterface
     */
    private $batchSend;

    /**
     * Send constructor.
     * @param BatchSendInterface $batchSend
     * @param string|null $name
     */
    public function __construct(
        BatchSendInterface $batchSend,
        string $name = null
    ) {
        parent::__construct($name);

        $this->batchSend = $batchSend;
    }

    protected function configure()
    {
        $this->setName('linktracker:send')
            ->setDescription('Notify about orders missing');

        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Start sending tracking information');

        $this->batchSend->execute();

        $output->writeln('Finished sending tracking information');
    }

}
