<?php

namespace Linktracker\Tracking\Observer;

use Linktracker\Tracking\Api\Config as TrackingConfig;
use Linktracker\Tracking\Model\CookieInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;

class Redirect implements ObserverInterface
{
    /**
     * @var CookieInterface
     */
    private $cookie;

    private $response;

    private $request;

    private $doRedirect = true;

    public function __construct(
        CookieInterface $cookie
    ) {
        $this->cookie = $cookie;
    }

    public function execute(Observer $observer)
    {
        $this->request = $observer->getRequest();
        $this->response = $observer->getControllerAction()->getResponse();

        if ($this->request->getMethod() != 'GET') {
            return;
        }

        $trackerId = $this->request->getParam(TrackingConfig::TRACKING_REQUEST_PARAMETER);
        if (! $trackerId || ! $this->doRedirect) {
            return;
        }

        $this->cookie->setValue($trackerId);

        $this->request->setDispatched(false);
        $this->setRedirect($this->request->getOriginalPathInfo());
        $this->doRedirect = false;
    }

    public function setRedirect($redirectUrl)
    {
        $this->response->setRedirect($redirectUrl, 301)->sendResponse();
    }
}
