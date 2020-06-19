<?php

namespace Linktracker\Tracking\Observer;

use Linktracker\Tracking\Api\ConfigInterface;
use Linktracker\Tracking\Api\ConfigInterface as TrackingConfig;
use Linktracker\Tracking\Model\CookieInterface;
use Magento\Framework\App\HttpRequestInterface;
use Magento\Framework\App\Response\HttpInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class Redirect implements ObserverInterface
{
    /**
     * @var CookieInterface
     */
    private $cookie;

    private $runOnce = false;

    public function __construct(
        CookieInterface $cookie
    ) {
        $this->cookie = $cookie;
    }

    public function execute(Observer $observer)
    {
        if ($this->runOnce) {
            return;
        }

        /** @var HttpRequestInterface $request */
        $request = $observer->getEvent()
                ->getData('request');

        if (! $request->isGet()) {
            return;
        }

        $trackerId = $request->getParam(TrackingConfig::TRACKING_REQUEST_PARAMETER);
        if (! $trackerId) {
            return;
        }
        $this->cookie->setValue($trackerId);

        $request->setDispatched(false);

        /** @var HttpInterface $response */
        $response = $observer->getEvent()
                ->getData('controller_action')
                ->getResponse();

        $this->setRedirect($request, $response);
        $this->runOnce = true;
    }

    public function setRedirect(HttpRequestInterface $request, HttpInterface $response): void
    {
        $url = $this->removeLinkTrackerParam($request->getOriginalPathInfo());

        $response->setRedirect($url, 301);
        $response->sendResponse();
    }

    /**
     * @param $pathInfo
     * @return string|string[]|null
     */
    public function removeLinkTrackerParam($pathInfo): string
    {
        return rtrim(preg_replace(
            '#' .
            '(&|\?)' .
            preg_quote(ConfigInterface::TRACKING_REQUEST_PARAMETER, '#') .
            '=[^&]+(&|$)' .
            '#',
            '\\1',
            $pathInfo
        ), '?&');
    }
}
