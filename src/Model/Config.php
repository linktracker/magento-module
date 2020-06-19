<?php

namespace Linktracker\Tracking\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;

class Config implements ConfigInterface
{
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    public function __construct(
        ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    public function isEnabled(int $storeId): bool
    {
        return $this->scopeConfig->getValue(
            static::XML_PATH_LINKTRACKER_TRACKING_ENABLED,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    public function getTrackingUrl(int $storeId): string
    {
        return (string)$this->scopeConfig->getValue(
            static::XML_PATH_LINKTRACKER_TRACKING_URL,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }
}
