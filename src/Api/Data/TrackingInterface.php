<?php

namespace Linktracker\Tracking\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface TrackingInterface extends ExtensibleDataInterface
{
    public const TRACKING_ID            = 'tracking_id';
    public const ORDER_ID               = 'order_id';
    public const ORDER_INCREMENTED_ID   = 'order_increment_id';
    public const GRAND_TOTAL            = 'grand_total';
    public const STORE_ID               = 'store_id';
    public const STATUS                 = 'status';

    public function getId();

    public function setId($value);

    public function getTrackingId(): string;

    public function setTrackingId(string $trackingId): void;

    public function getOrderId(): int;

    public function setOrderId(int $orderId): void;

    public function getOrderIncrementId(): string;

    public function setOrderIncrementId(string $OrderIncrementedId): void;

    public function getGrandTotal(): float;

    public function setGrandTotal(float $grandTotal): void;

    public function getStoreId(): int;

    public function setStoreId(int $storeId): void;

    public function getStatus(): int;

    public function setStatus(int $status): void;

    /**
     * Retrieve existing extension attributes object or create a new one.
     *
     * @return \TrackingInterface\Tracking\Api\Data\TrackingExtensionInterface|null
     */
    public function getExtensionAttributes();

    /**
     * Set an extension attributes object.
     *
     * @param \TrackingInterface\Tracking\Api\Data\TrackingExtensionInterface $extensionAttributes
     * @return $this
     */
    public function setExtensionAttributes(\TrackingInterface\Tracking\Api\Data\TrackingExtensionInterface $extensionAttributes);
}
