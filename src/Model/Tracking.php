<?php

namespace Linktracker\Tracking\Model;

use Magento\Framework\Model\AbstractExtensibleModel;
use Linktracker\Tracking\Api\Data\TrackingInterface;
use Linktracker\Tracking\Api\Data\TrackingExtensionInterface;

class Tracking extends AbstractExtensibleModel implements TrackingInterface
{

    public function _construct()
    {
        $this->_init(ResourceModel\Tracking::class);
    }

    public function getTrackingId(): string
    {
        return $this->_getData(static::TRACKING_ID);
    }

    public function setTrackingId(string $trackingId): void
    {
        $this->setData(static::TRACKING_ID, $trackingId);
    }

    public function getOrderId(): int
    {
        return $this->_getData(static::ORDER_ID);
    }

    public function setOrderId(int $orderId): void
    {
        $this->setData(static::ORDER_ID, $orderId);
    }

    public function getOrderIncrementId(): string
    {
        return $this->_getData(static::ORDER_INCREMENTED_ID);
    }

    public function setOrderIncrementId(string $incrementedOrderId): void
    {
        $this->setData(static::ORDER_INCREMENTED_ID, $incrementedOrderId);
    }

    public function getGrandTotal(): float
    {
        return $this->_getData(static::GRAND_TOTAL);
    }

    public function setGrandTotal(float $grandTotal): void
    {
        $this->setData(static::GRAND_TOTAL, $grandTotal);
    }

    public function getStatus(): int
    {
        return $this->_getData(static::STATUS);
    }

    public function setStatus(int $status): void
    {
        $this->setData(static::STATUS, $status);
    }

    public function getStoreId(): int
    {
        return $this->_getData(static::STORE_ID);
    }

    public function setStoreId(int $storeId): void
    {
        $this->setData(static::STORE_ID, $storeId);
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
