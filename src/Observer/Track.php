<?php

namespace Linktracker\Tracking\Observer;

use Linktracker\Tracking\Model\Cookie;
use Linktracker\Tracking\Model\TrackingFactory;
use Linktracker\Tracking\Model\TrackingRepository;
use Magento\Framework\Event\Observer;
use Linktracker\Tracking\Api\Config as StatusConfig;

class Track implements \Magento\Framework\Event\ObserverInterface
{
    /**
     * @var TrackingRepository
     */
    protected $trackingRepository;

    /**
     * @var Cookie
     */
    protected $cookie;

    /**
     * @var TrackingFactory
     */
    protected $tracking;

    public function __construct(
        TrackingRepository $trackingRepository,
        TrackingFactory $tracking,
        Cookie $cookie
    ) {
        $this->trackingRepository = $trackingRepository;
        $this->cookie = $cookie;
        $this->tracking = $tracking;
    }

    public function execute(Observer $observer)
    {
        if (! $this->cookie->exists()) {
            return;
        }

        $trackingId = $this->cookie->getValue();
        $order = $observer->getEvent()->getData('order');
        $this->saveTracking(
            $trackingId,
            $order->getId(),
            $order->getIncrementId(),
            $order->getGrandTotal(),
            StatusConfig::STATUS_NEW);
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
