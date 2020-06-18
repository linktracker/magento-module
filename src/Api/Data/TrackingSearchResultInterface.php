<?php

namespace Linktracker\Tracking\Api\Data;

use Magento\Framework\Api\SearchResultsInterface;

interface TrackingSearchResultInterface extends SearchResultsInterface
{
    /**
     * @return \Linktracker\Tracking\Api\Data\TrackingInterface[]
     */
    public function getItems();

    /**
     * @param \Linktracker\Tracking\Api\Data\TrackingInterface[] $items
     * @return void
     */
    public function setItems(array $items);
}
