<?php

namespace Linktracker\Tracking\Model;

use Magento\Framework\Model\AbstractExtensibleModel;
use Linktracker\Tracking\Api\Data\TrackingInterface;
use TrackingInterface\Tracking\Api\Data\TrackingExtensionInterface;

class Tracking extends AbstractExtensibleModel implements TrackingInterface
{
    const TRACKING_ID = 'tracking_id';
    const ORDER_ID = 'order_id';
    const ORDER_INCREMENTED_ID = 'order_increment_id';
    const GRAND_TOTAL = 'grand_total';
    const STATUS = 'status';

    public function _construct()
    {
        $this->_init(ResourceModel\Tracking::class);
    }

    public function getTrackingId(): string
    {
        return $this->_getData(self::TRACKING_ID);
    }

    public function setTrackingId(string $trackingId): void
    {
        $this->setData(self::TRACKING_ID, $trackingId);
    }

    public function getOrderId(): int
    {
        return $this->_getData(self::ORDER_ID);
    }

    public function setOrderId(int $orderId): void
    {
        $this->setData(self::ORDER_ID, $orderId);
    }

    public function getOrderIncrementId(): string
    {
        return $this->_getData(self::ORDER_INCREMENTED_ID);
    }

    public function setOrderIncrementId(string $incrementedOrderId): void
    {
        $this->setData(self::ORDER_INCREMENTED_ID, $incrementedOrderId);
    }

    public function getGrandTotal(): float
    {
        return $this->_getData(self::GRAND_TOTAL);
    }

    public function setGrandTotal(float $grandTotal): void
    {
        $this->setData(self::GRAND_TOTAL, $grandTotal);
    }

    public function getStatus(): int
    {
        return $this->_getData(self::STATUS);
    }

    public function setStatus(int $status): void
    {
        $this->setData(self::STATUS, $status);
    }

    public function getExtensionAttributes()
    {
        return $this->_getExtensionAttributes();
    }

    public function setExtensionAttributes(TrackingExtensionInterface $extensionAttributes)
    {
        $this->_setExtensionAttributes($extensionAttributes);
    }
}
