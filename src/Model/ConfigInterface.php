<?php


namespace Linktracker\Tracking\Model;


interface ConfigInterface
{
    public const XML_PATH_LINKTRACKER_TRACKING_ENABLED  = 'linktracker/general/enabled';
    public const XML_PATH_LINKTRACKER_TRACKING_URL      = 'linktracker/general/tracking_url';

    /**
     * Tell if module is enabled
     *
     * @param int $storeId
     * @return bool
     */
    public function isEnabled(int $storeId): bool;

    /**
     * Get Tracking URL
     *
     * @param int $storeId
     * @return string
     */
    public function getTrackingUrl(int $storeId): string;

}
