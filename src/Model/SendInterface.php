<?php


namespace Linktracker\Tracking\Model;


use Linktracker\Tracking\Api\Data\TrackingInterface;

interface SendInterface
{

    /**
     * Prepare data and send over to receiving party
     *
     * @param TrackingInterface $tracking
     * @return bool
     */
    public function sendTrackingData(TrackingInterface $tracking): bool;

}
