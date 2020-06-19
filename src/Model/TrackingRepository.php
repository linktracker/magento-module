<?php

namespace Linktracker\Tracking\Model;

use Linktracker\Tracking\Api\ConfigInterface;
use Linktracker\Tracking\Api\TrackingRepositoryInterface;
use Linktracker\Tracking\Api\Data\TrackingInterface;
use Linktracker\Tracking\Api\Data\TrackingSearchResultInterfaceFactory;
use Linktracker\Tracking\Model\ResourceModel\Tracking as TrackingResource;
use Linktracker\Tracking\Model\ResourceModel\Tracking\CollectionFactory as TrackingCollectionFactory;
use Linktracker\Tracking\Model\ResourceModel\Tracking\Collection;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\NoSuchEntityException;

class TrackingRepository implements TrackingRepositoryInterface
{
    /**
     * @var TrackingInterfaceFactory
     */
    private $trackingFactory;
    /**
     * @var TrackingCollectionFactory
     */
    private $trackingCollectionFactory;
    /**
     * @var TrackingSearchResultInterfaceFactory
     */
    private $trackingSearchResultInterfaceFactory;
    /**
     * @var CollectionProcessorInterface
     */
    private $collectionProcessor;
    /**
     * @var Linktracker\Tracking\Model\ResourceModel\Tracking
     */
    private $resourceModel;

    public function __construct(
        TrackingInterfaceFactory $trackingFactory,
        TrackingResource $resourceModel,
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
        /* @var Collection $collection */
        $collection = $this->trackingCollectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);
        $searchResults = $this->trackingSearchResultInterfaceFactory->create();

        $searchResults->setSearchCriteria($searchCriteria);
        $searchResults->setItems($collection->getItems());

        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function createTracking(
        string $trackingCode,
        int $orderId,
        string $orderIncrementId = '',
        float $amount = 0.0,
        int $status = ConfigInterface::STATUS_NEW
    ): TrackingInterface {

        /* @var TrackingInterface $tracking */
        $tracking = $this->trackingFactory->create();

        $tracking->setTrackingId($trackingCode);
        $tracking->setOrderId($orderId);
        $tracking->setOrderIncrementId($orderIncrementId);
        $tracking->setGrandTotal($amount);
        $tracking->setStatus($status);

        return $tracking;
    }
}
