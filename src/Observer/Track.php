<?php

namespace Linktracker\Tracking\Observer;

use Linktracker\Tracking\Api\TrackingRepositoryInterface;
use Linktracker\Tracking\Model\CookieInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Sales\Api\Data\OrderInterface;

class Track implements ObserverInterface
{
    /**
     * @var TrackingRepositoryInterface
     */
    private $trackingRepository;

    /**
     * @var CookieInterface
     */
    private $cookie;

    /**
     * Track constructor.
     * @param TrackingRepositoryInterface $trackingRepository
     * @param CookieInterface $cookie
     */
    public function __construct(
        TrackingRepositoryInterface $trackingRepository,
        CookieInterface $cookie
    ) {
        $this->trackingRepository = $trackingRepository;
        $this->cookie = $cookie;
    }

    public function execute(Observer $observer)
    {
        if (! $this->cookie->exists()) {
            return;
        }

        /** @var OrderInterface $order */
        $order = $observer->getEvent()
            ->getData('order');

        $trackingId = $this->cookie->getValue();

        $repository = $this->trackingRepository;
        $tracking = $repository->createTracking(
            $trackingId,
            $order->getEntityId(),
            $order->getIncrementId(),
            $order->getGrandTotal(),
            $order->getStoreId()
        );

        $repository->save($tracking);
    }

}
