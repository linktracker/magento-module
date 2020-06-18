<?php

namespace Linktracker\Tracking\Model;

use Linktracker\Tracking\Api\Config as StatusConfig;

class BatchSend implements BatchSendInterface
{
    /**
     * @var Send
     */
    protected $send;

    /**
     * @var TrackingRepository
     */
    protected $trackingRepository;

    /**
     * @var \Magento\Framework\Api\SearchCriteriaBuilder
     */
    protected $searchCriteriaBuilder;

    public function __construct(
        \Linktracker\Tracking\Model\Send $send,
        \Linktracker\Tracking\Model\TrackingRepository $trackingRepository,
        \Magento\Framework\Api\SearchCriteriaBuilder $searchCriteriaBuilder
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
