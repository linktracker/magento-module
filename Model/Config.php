<?php

namespace Linktracker\Tracking\Model;

use Magento\Store\Model\ScopeInterface;

class Config
{
    const XML_PATH_LINKTRACKER = 'linktracker/';

    const XML_PATH_GENERAL = 'general/';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->scopeConfig = $scopeConfig;
    }

    public function getConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $field, ScopeInterface::SCOPE_STORE, $storeId
        );
    }

    public function getGeneralConfigValue($field, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            self::XML_PATH_LINKTRACKER . self::XML_PATH_GENERAL . $field, ScopeInterface::SCOPE_STORE, $storeId
        );
    }
}
