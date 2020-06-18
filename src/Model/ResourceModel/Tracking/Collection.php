<?php

namespace Linktracker\Tracking\Model\ResourceModel\Tracking;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    public function _construct()
    {
        $this->_init("Linktracker\Tracking\Model\Tracking", "\Linktracker\Tracking\Model\ResourceModel\Tracking");
    }
}
