<?php

namespace Linktracker\Tracking\Model;

use \Linktracker\Tracking\Api\Config as CookieConfig;
use \Magento\Framework\Stdlib\Cookie\CookieMetadata;

class Cookie
{
    /**
     * @var \Magento\Framework\Stdlib\CookieManagerInterface
     */
    protected $cookieManager;

    /**
     * @var \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory
     */
    protected $cookieMetadata;

    /**
     * @var \Magento\Framework\Session\SessionManagerInterface
     */
    protected $sessionManager;

    public function __construct(
        \Magento\Framework\Stdlib\CookieManagerInterface $cookieManager,
        \Magento\Framework\Stdlib\Cookie\CookieMetadataFactory $cookieMetadata,
        \Magento\Framework\Session\SessionManagerInterface $sessionManager
    ) {
        $this->cookieManager = $cookieManager;
        $this->cookieMetadata = $cookieMetadata;
        $this->sessionManager = $sessionManager;
    }

    /**
     * @param string $value
     * @param int $duration
     * @throws \Magento\Framework\Exception\InputException
     * @throws \Magento\Framework\Stdlib\Cookie\CookieSizeLimitReachedException
     * @throws \Magento\Framework\Stdlib\Cookie\FailureToSendException
     */
    public function setCookie(string $value, int $duration = CookieConfig::COOKIE_DURATION): void
    {
        $metadata = $this->cookieMetadata
            ->createSensitiveCookieMetadata([CookieMetadata::KEY_DURATION => $duration])
            ->setPath($this->sessionManager->getCookiePath())
            ->setDomain($this->sessionManager->getCookieDomain());
        $this->cookieManager->setSensitiveCookie($this->getCookieName(), $value, $metadata);
    }

    /**
     * @return string|null
     */
    public function getCookie()
    {
        return $this->cookieManager->getCookie($this->getCookieName());
    }

    public function hasCookie(): bool {
        return empty($this->cookieManager->getCookie($this->getCookieName())) ? false : true;
    }

    public function getCookieName() {
        return CookieConfig::COOKIE_NAME;
    }
}
