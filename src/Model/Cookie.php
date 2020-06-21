<?php

namespace Linktracker\Tracking\Model;

use Linktracker\Tracking\Api\ConfigInterface as CookieConfig;
use Magento\Framework\Session\Config\ConfigInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadata;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;

class Cookie implements CookieInterface
{
    /**
     * @var CookieManagerInterface
     */
    private $cookieManager;
    /**
     * @var CookieMetadataFactory
     */
    private $cookieMetadata;
    /**
     * @var string
     */
    private $cookieName;
    /**
     * @var float|int
     */
    private $cookieDuration;
    /**
     * @var ConfigInterface
     */
    private $cookieConfig;

    /**
     * Cookie constructor.
     * @param CookieManagerInterface $cookieManager
     * @param CookieMetadataFactory $cookieMetadata
     * @param ConfigInterface $cookieConfig
     * @param string $cookieName
     * @param float|int $cookieDuration
     */
    public function __construct(
        CookieManagerInterface $cookieManager,
        CookieMetadataFactory $cookieMetadata,
        ConfigInterface $cookieConfig,
        string $cookieName = CookieConfig::COOKIE_NAME,
        int $cookieDuration = CookieConfig::COOKIE_DURATION
    ) {
        $this->cookieManager = $cookieManager;
        $this->cookieMetadata = $cookieMetadata;
        $this->cookieName = $cookieName;
        $this->cookieDuration = $cookieDuration;
        $this->cookieConfig = $cookieConfig;
    }

    /**
     * @inheritDoc
     */
    public function getName(): string
    {
        return $this->cookieName;
    }

    /**
     * @inheritDoc
     */
    public function getDuration(): int
    {
        return $this->cookieDuration;
    }

    /**
     * @inheritDoc
     */
    public function setValue(string $value): void
    {
        $metadata = $this->cookieMetadata->createSensitiveCookieMetadata([CookieMetadata::KEY_DURATION => $this->getDuration()]);
        $metadata->setPath($this->cookieConfig->getCookiePath());
        $metadata->setDomain($this->cookieConfig->getCookieDomain());

        $this->cookieManager->setSensitiveCookie($this->getName(), $value, $metadata);
    }

    /**
     * @inheritDoc
     */
    public function getValue(): string
    {
        return (string)$this->cookieManager->getCookie($this->getName());
    }

    /**
     * @inheritDoc
     */
    public function exists(): bool
    {
        return ! empty($this->getValue());
    }
}
