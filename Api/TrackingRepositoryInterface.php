<?php

namespace Linktracker\Tracking\Api;

use Magento\Framework\Api\SearchCriteriaInterface;
use Linktracker\Tracking\Api\Data\TrackingInterface;

interface TrackingRepositoryInterface
{
    /**
     * @param int $id
     * @return \Linktracker\Tracking\Api\Data\TrackingInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getById($id);

    /**
     * @param \Linktracker\Tracking\Api\Data\TrackingInterface $tracking
     * @return \Linktracker\Tracking\Api\Data\TrackingInterface
     */
    public function save(TrackingInterface $tracking);

    /**
     * @param \Linktracker\Tracking\Api\Data\TrackingInterface $tracking
     * @return void
     */
    public function delete(TrackingInterface $tracking);

    /**
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Linktracker\Tracking\Api\Data\TrackingSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);
}
