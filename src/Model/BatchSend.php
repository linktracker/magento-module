<?php

namespace Linktracker\Tracking\Model;

use Linktracker\Tracking\Api\ConfigInterface as StatusConfig;
use Linktracker\Tracking\Api\TrackingRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

class BatchSend implements BatchSendInterface
{
    /**
     * @var Send
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
        Send $send,
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
        foreach ($items->getItems() as $item) {
            /* @var \Linktracker\Tracking\Model\Tracking $item */
            $result = $this->send->sendTrackingData(
                $item->getTrackingId(),
                $item->getOrderIncrementId(),
                $item->getGrandTotal()
            );

            $status = $result ? StatusConfig::STATUS_SEND : StatusConfig::STATUS_FAILED;
            $this->updateStatus($item, $status);
        }
    }

    public function updateStatus(\Linktracker\Tracking\Model\Tracking $item, int $status): void
    {
        $item->setStatus($status);
        $this->trackingRepository->save($item);
    }

    /**
     * @param int $statusId
     * @return \Magento\Framework\Api\SearchCriteria
     */
    public function getStatusFilter(int $statusId): \Magento\Framework\Api\SearchCriteria
    {
        return $this->searchCriteriaBuilder->addFilter('status', $statusId)->create();
    }
}
