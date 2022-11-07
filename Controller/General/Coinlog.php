<?php

namespace Vbhex\Checkout\Controller\General;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Vbhex Checkout Public Coin Log Page Controller.
 */
class Coinlog extends Action
{
    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @param Context     $context
     * @param PageFactory $resultPageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory
    ) {
        $this->_resultPageFactory = $resultPageFactory;
        parent::__construct($context);
    }

    /**
     * Dservice Public Coin Log Detail page.
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $order_id = $this->getRequest()->getParam('order');
        $coin_id = $this->getRequest()->getParam('coin');
        $wallet = $this->getRequest()->getParam('wallet');
        try {
            $resultPage = $this->_resultPageFactory->create();
            $resultPage->getConfig()->getTitle()->set(__('Coin Logs'));
            $block = $resultPage->getLayout()->getBlock('vbhexcheckout_general_coinlog');
            if(!empty($order_id)) {
                $block->setData('order_id', $order_id);
            }
            if(!empty($coin_id)) {
                $block->setData('coin_id', $coin_id);
            }
            if(!empty($wallet)) {
                $block->setData('wallet', $wallet);
            }
            return $resultPage;
        } catch (Exception $e) {
        }
        $resultRedirect = $this->resultRedirectFactory->create();
        $resultRedirect->setPath('/');
        return $resultRedirect;
    }
}
