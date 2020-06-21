<?php

namespace Linktracker\Tracking\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use PHPUnit\Framework\TestCase;

class ConfigTest extends TestCase
{

    /**
     * @var ScopeConfigInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $scopeConfigMock;
    /**
     * @var Config
     */
    private $config;

    protected function setUp(): void
    {
        $this->scopeConfigMock = $this->createMock(ScopeConfigInterface::class);
        $this->config = new Config(
            $this->scopeConfigMock
        );
    }

    public function testGetTrackingUrl()
    {
        $config = $this->config;

        $scopeConfigMock = $this->scopeConfigMock;

        $scopeConfigMock->expects($this->once())
                ->method('getValue')
                ->with(ConfigInterface::XML_PATH_LINKTRACKER_TRACKING_URL, ScopeInterface::SCOPE_STORE, 1)
                ->willReturn('test');

        $this->assertSame('test', $config->getTrackingUrl(1));
    }

    public function testIsEnabled()
    {
        $config = $this->config;

        $scopeConfigMock = $this->scopeConfigMock;

        $scopeConfigMock->expects($this->exactly(2))
            ->method('getValue')
            ->with(ConfigInterface::XML_PATH_LINKTRACKER_TRACKING_ENABLED, ScopeInterface::SCOPE_STORE, 1)
            ->willReturn(0, 1);

        $this->assertFalse($config->isEnabled(1));
        $this->assertTrue($config->isEnabled(1));
    }
}
