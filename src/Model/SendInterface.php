<?php


namespace Linktracker\Tracking\Model;


interface SendInterface
{
    /**
     * Prepare data and send over to receiving party
     *
     * @param string $trackingCode
     * @param string $incrementId
     * @param float $orderAmount
     * @param int $storeId
     * @return bool
     */
    public function sendTrackingData(string $trackingCode, string $incrementId, float $orderAmount, int $storeId): bool;

}
