<?php

namespace Linktracker\Tracking\Api\Data;

use Magento\Framework\Api\ExtensibleDataInterface;
use Magento\Tests\NamingConvention\true\float;

interface TrackingInterface extends ExtensibleDataInterface
{
    public function getId(): int;

    public function setId($value);

    public function getTrackingId(): int;

    public function setTrackingId(int $trackingId): void;

    public function getOrderId(): int;

    public function setOrderId(int $orderId): void;

    public function getIncrementedOrderId(): string;

    public function setIncrementedOrderId(string $incrementedOrderId): void;

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
