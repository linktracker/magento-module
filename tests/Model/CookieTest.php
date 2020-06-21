<?php

namespace Linktracker\Tracking\Model;

use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Session\Config\ConfigInterface as CookieConfigInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadata;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\Cookie\SensitiveCookieMetadata;
use Magento\Framework\Stdlib\CookieManagerInterface;
use PHPUnit\Framework\TestCase;

class CookieTest extends TestCase
{

    /**
     * @var CookieManagerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $cookieManagerMock;
    /**
     * @var Cookie
     */
    private $cookie;
    /**
     * @var CookieMetadataFactory|\PHPUnit\Framework\MockObject\MockObject
     */
    private $cookieMetaDataMock;
    /**
     * @var ObjectManagerInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $objectManagerInterfaceMock;
    /**
     * @var CookieConfigInterface|\PHPUnit\Framework\MockObject\MockObject
     */
    private $cookieConfigMock;

    protected function setUp(): void
    {
        $this->cookieManagerMock = $this->createMock(CookieManagerInterface::class);

        $this->objectManagerInterfaceMock = $this->createMock(ObjectManagerInterface::class);

        $this->cookieMetaDataMock = $this->getMockBuilder(CookieMetadataFactory::class)
                ->setConstructorArgs([$this->objectManagerInterfaceMock])
                ->getMock();

        $this->cookieConfigMock = $this->createMock(CookieConfigInterface::class);

        $this->cookie = new Cookie(
            $this->cookieManagerMock,
            $this->cookieMetaDataMock,
            $this->cookieConfigMock
        );
    }

    public function testGetValue()
    {
        $cookie = $this->cookie;
        $cookieManager = $this->cookieManagerMock;

        $cookieManager->expects($this->once())
                ->method('getCookie')
                ->willReturn('test');

        $this->assertSame('test', $cookie->getValue());
    }

    public function testGetName()
    {
        $this->assertSame(\Linktracker\Tracking\Api\ConfigInterface::COOKIE_NAME, $this->cookie->getName());

        $cookie = new Cookie(
            $this->cookieManagerMock,
            $this->cookieMetaDataMock,
            $this->cookieConfigMock,
            'test'
        );

        $this->assertSame('test', $cookie->getName());

    }

    public function testGetDuration()
    {
        $this->assertSame(\Linktracker\Tracking\Api\ConfigInterface::COOKIE_DURATION, $this->cookie->getDuration());

        $cookie = new Cookie(
            $this->cookieManagerMock,
            $this->cookieMetaDataMock,
            $this->cookieConfigMock,
            'test',
            50
        );

        $this->assertSame(50, $cookie->getDuration());
    }

    public function testSetValue()
    {
        $cookie = $this->cookie;

        $cookieMetaDataMock = $this->cookieMetaDataMock;
        $cookieManagerMock = $this->cookieManagerMock;
        $cookieConfigMock = $this->cookieConfigMock;

        $sensativeCookieMock = $this->createMock(SensitiveCookieMetadata::class);

        $cookieMetaDataMock->expects($this->once())
                ->method('createSensitiveCookieMetadata')
                ->with([CookieMetadata::KEY_DURATION => $cookie->getDuration()])
                ->willReturn($sensativeCookieMock);

        $sensativeCookieMock->expects($this->once())
                ->method('setPath');
        $sensativeCookieMock->expects($this->once())
                ->method('setDomain');

        $cookieConfigMock->expects($this->once())
                ->method('getCookiePath');
        $cookieConfigMock->expects($this->once())
                ->method('getCookieDomain');

        $cookieManagerMock->expects($this->once())
                ->method('setSensitiveCookie')
                ->with($cookie->getName(), 'hello');


        $cookie->setValue('hello');
    }

    public function testExists()
    {
        $cookie = $this->cookie;

        $cookieManagerMock = $this->cookieManagerMock;

        $cookieManagerMock->method('getCookie')
                ->willReturn(null, 'test');


        $this->assertFalse($cookie->exists());
        $this->assertTrue($cookie->exists());

    }
}
