<?php

namespace Linktracker\Tracking\Model;

use Linktracker\Tracking\Api\TrackingRepositoryInterface;
use Linktracker\Tracking\Api\Data\TrackingInterface;
use Linktracker\Tracking\Api\Data\TrackingSearchResultInterface;
use Linktracker\Tracking\Api\Data\TrackingSearchResultInterfaceFactory;
use Linktracker\Tracking\Model\ResourceModel\Tracking\CollectionFactory as TrackingCollectionFactory;
use Linktracker\Tracking\Model\ResourceModel\Tracking\Collection;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Api\SortOrder;
use Magento\Framework\Exception\NoSuchEntityException;

class TrackingRepository implements TrackingRepositoryInterface
{
    /**
     * @var TrackingFactory
     */
    protected $trackingFactory;

    /**
     * @var TrackingCollectionFactory
     */
    protected $trackingCollectionFactory;

    /**
     * @var TrackingSearchResultInterfaceFactory
     */
    protected $trackingSearchResultInterfaceFactory;

    /**
     * @var CollectionProcessorInterface
     */
    protected $collectionProcessor;

    /**
     * @var Linktracker\Tracking\Model\ResourceModel\Tracking
     */
    protected $resourceModel;

    public function __construct(
        TrackingFactory $trackingFactory,
        \Linktracker\Tracking\Model\ResourceModel\Tracking $resourceModel,
        TrackingCollectionFactory $trackingCollectionFactory,
        TrackingSearchResultInterfaceFactory $trackingSearchResultInterfaceFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->trackingFactory = $trackingFactory;
        $this->trackingCollectionFactory = $trackingCollectionFactory;
        $this->trackingSearchResultInterfaceFactory = $trackingSearchResultInterfaceFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->resourceModel = $resourceModel;
    }
    /**
     * @inheritDoc
     */
    public function getById($id)
    {
        /* @var \Linktracker\Tracking\Model\Tracking $tracking */
        $tracking = $this->trackingFactory->create();
        $tracking->load($id);
        if (! $tracking->getId()) {
            throw new NoSuchEntityException(__('Unable to find tracking with ID "%1"', $id));
        }
        return $tracking;
    }

    /**
     * @inheritDoc
     */
    public function save(TrackingInterface $tracking)
    {
        return $this->resourceModel->save($tracking);
    }

    /**
     * @inheritDoc
     */
    public function delete(TrackingInterface $tracking)
    {
        $this->resourceModel->delete($tracking);
    }

    /**
     * @inheritDoc
     */
    public function getList(SearchCriteriaInterface $searchCriteria)
    {
        /* @var \Linktracker\Tracking\Model\ResourceModel\Tracking\Collection $collection */
        $collection = $this->trackingCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResults = $this->trackingSearchResultInterfaceFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());

        return $searchResults;
    }
}
