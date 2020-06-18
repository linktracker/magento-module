<?php

namespace Linktracker\Tracking\Console\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;

class Send extends \Symfony\Component\Console\Command\Command
{
    /**
     * @var \Linktracker\Tracking\Model\BatchSend
     */
    protected $batchSend;

    /**
     * @var \Magento\Framework\App\State
     */
    protected $state;

    /**
     * @var \Linktracker\Tracking\Model\TrackingRepository
     */
    protected $trackingRepository;

    /**
     * @var \Linktracker\Tracking\Model\TrackingFactory
     */
    protected $tracking;

    public function __construct(
        \Linktracker\Tracking\Model\BatchSend $batchSend,
        \Linktracker\Tracking\Model\TrackingRepository $trackingRepository,
        \Linktracker\Tracking\Model\TrackingFactory $tracking,
        \Magento\Framework\App\State $state,
        string $name = null
    ) {
        parent::__construct($name);

        $this->batchSend = $batchSend;
        $this->state = $state;
        $this->trackingRepository = $trackingRepository;
        $this->tracking = $tracking;
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

//        $tracking = $this->trackingRepository->getById(4);
//        $this->trackingRepository->delete($tracking);

//        $this->saveTracking('gideon', 3, 'AA3', 21.95, 1);
//        $this->saveTracking('gideon', 4, 'AA4', 25.95, 1);
        $this->state->setAreaCode(\Magento\Framework\App\Area::AREA_ADMINHTML);

        $this->batchSend->execute();

        $output->writeln('Finished sending tracking information');
    }

    public function saveTracking(
        string $trackingId,
        int $orderId,
        string $orderIncrementId,
        float $orderAmount,
        int $status
    ) {
        /* @var \Linktracker\Tracking\Model\Tracking $tracking */
        $tracking = $this->tracking->create();
        $tracking->setTrackingId($trackingId);
        $tracking->setOrderId($orderId);
        $tracking->setOrderIncrementId($orderIncrementId);
        $tracking->setGrandTotal($orderAmount);
        $tracking->setStatus($status);
        $this->trackingRepository->save($tracking);
    }
}
