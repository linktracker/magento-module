<?php

namespace Linktracker\Tracking\Api;

use Linktracker\Tracking\Api\Data\TrackingSearchResultInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Linktracker\Tracking\Api\Data\TrackingInterface;
use Magento\Framework\Exception\NoSuchEntityException;

interface TrackingRepositoryInterface
{
    /**
     * @param int $id
     * @return TrackingInterface
     * @throws NoSuchEntityException
     */
    public function getById($id);

    /**
     * @param TrackingInterface $tracking
     * @return TrackingInterface
     */
    public function save(TrackingInterface $tracking);

    /**
     * @param TrackingInterface $tracking
     * @return void
     */
    public function delete(TrackingInterface $tracking);

    /**
     * @param SearchCriteriaInterface $searchCriteria
     * @return TrackingSearchResultInterface
     */
    public function getList(SearchCriteriaInterface $searchCriteria);

}
