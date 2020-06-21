<?php

namespace Linktracker\Tracking\Model;

use Linktracker\Tracking\Api\ConfigInterface as StatusConfig;
use Linktracker\Tracking\Api\Data\TrackingInterface;
use Linktracker\Tracking\Api\TrackingRepositoryInterface;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SearchCriteriaBuilder;

class BatchSend implements BatchSendInterface
{
    /**
     * @var SendInterface
     */
    private $send;
    /**
     * @var TrackingRepository
     */
    private $trackingRepository;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;

    public function __construct(
        SendInterface $send,
        TrackingRepositoryInterface $trackingRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->send = $send;
        $this->trackingRepository = $trackingRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function execute(): void
    {
        $items = $this->trackingRepository->getList($this->getStatusFilter(StatusConfig::STATUS_NEW));

        /* @var TrackingInterface $item */
        foreach ($items->getItems() as $item) {
            $result = $this->send->sendTrackingData(
                $item->getTrackingId(),
                $item->getOrderIncrementId(),
                $item->getGrandTotal(),
                $item->getStoreId()
            );

            $status = $result ? StatusConfig::STATUS_SEND : StatusConfig::STATUS_FAILED;
            $this->updateStatus($item, $status);
        }
    }

    public function updateStatus(TrackingInterface $item, int $status): void
    {
        $item->setStatus($status);
        $this->trackingRepository->save($item);
    }

    /**
     * @param int $statusId
     * @return SearchCriteria
     */
    public function getStatusFilter(int $statusId): SearchCriteria
    {
        return $this->searchCriteriaBuilder
                ->addFilter('status', $statusId)
                ->create();
    }
}
