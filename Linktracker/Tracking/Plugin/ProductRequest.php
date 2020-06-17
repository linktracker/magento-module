<?php

namespace Linktracker\Tracking\Plugin;

use \Linktracker\Tracking\Api\Config as TrackingConfig;
use Magento\Framework\Controller\ResultFactory;

class ProductRequest
{
    /**
     * @var \Linktracker\Tracking\Model\Cookie
     */
    protected $cookie;

    /**
     * @var \Magento\Framework\App\Response\RedirectInterface
     */
    protected $redirect;

    /**
     * @var \Magento\Framework\App\RequestInterface
     */
    protected $request;

    /**
     * @var \Magento\Framework\Controller\ResultFactory
     */
    protected $resultFactory;

    public function __construct(
        \Linktracker\Tracking\Model\Cookie $cookie,
        \Magento\Framework\App\Response\RedirectInterface $redirect,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Controller\ResultFactory $resultFactory
    ) {
        $this->cookie = $cookie;
        $this->redirect = $redirect;
        $this->request = $request;
        $this->resultFactory = $resultFactory;
    }

    public function afterExecute($subject, $result)
    {
        $trackerId = $this->request->getParam(TrackingConfig::TRAKING_REQUEST_PARAMETER);
        if (! $trackerId) {
            return $result;
        }

        $this->cookie->setCookie($trackerId);
        return $this->redirect('/');
    }

    public function redirect($redirectUrl)
    {
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setUrl($redirectUrl);
        return $resultRedirect;
    }
}
