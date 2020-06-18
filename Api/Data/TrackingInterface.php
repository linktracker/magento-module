<?php

namespace Linktracker\Tracking\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;

interface TrackingInterface extends ExtensibleDataInterface
{
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
