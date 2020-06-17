<?php

namespace Linktracker\Tracking\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class Send extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var \Linktracker\Tracking\Model\Info
     */
    protected $info;

    /**
     * @var \Magento\Framework\App\State
     */
    protected $state;

    public function __construct(
        \Linktracker\Tracking\Model\Info $info,
        \Magento\Framework\App\State $state,
        string $name = null
    ) {
        parent::__construct($name);
        $this->info = $info;
        $this->state = $state;
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

        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);
        $this->info->execute();

        $output->writeln('Finished sending tracking information');
    }
}
