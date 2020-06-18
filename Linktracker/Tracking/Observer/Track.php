<?php

namespace Linktracker\Tracking\Observer;

use Linktracker\Tracking\Model\Tracking;
use Magento\Framework\Event\Observer;
use Linktracker\Tracking\Api\Config as StatusConfig;

class Track implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var \Linktracker\Tracking\Model\TrackingRepository
     */
    protected $trackingRepository;

    /**
     * @var \Linktracker\Tracking\Model\Cookie
     */
    protected $cookie;

    /**
     * @var \Linktracker\Tracking\Model\TrackingFactory
     */
    protected $tracking;

    public function __construct(
        \Linktracker\Tracking\Model\TrackingRepository $trackingRepository,
        \Linktracker\Tracking\Model\TrackingFactory $tracking,
        \Linktracker\Tracking\Model\Cookie $cookie
    ) {
        $this->trackingRepository = $trackingRepository;
        $this->cookie = $cookie;
        $this->tracking = $tracking;
    }

    public function execute(Observer $observer)
    {
        if (! $this->cookie->hasCookie()) {
            return;
        }

        $trackingId = $this->cookie->getCookie();
//        $this->saveTracking($trackingId);
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
