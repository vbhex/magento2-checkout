<?php

namespace Vbhex\Checkout\Controller\Dispute;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;

/**
 * Vbhex Checkout Public Dispute Detail Page Controller.
 */
class Detail extends Action
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
     * Dservice Public Dispute Detail page.
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
      $order_id = $this->getRequest()->getParam('order');
      $order = $this->getOrder($order_id);
      try{
         if($order->getId()){
               if (true
                //  && $order->getPayment()->getMethodInstance()->getCode() == 'vbhex_checkout'
                // && $order->getStatus() == "pending"
                ) {
                  $resultPage = $this->_resultPageFactory->create();
                  $resultPage->getConfig()->getTitle()->prepend(__('Dispute Detail #'.$order->getIncrementId()));
                  $block = $resultPage->getLayout()->getBlock('vbhexcheckout_dispute_detail');
                  $block->setData('order_id', $order_id);
                  return $resultPage;
               }
         }
      }catch(Exception $e){

      }
      $resultRedirect = $this->resultRedirectFactory->create();
      $resultRedirect->setPath('/');
      return $resultRedirect;
    }

    public function getOrder($_order_id){

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $order = $objectManager->create('Magento\Sales\Api\Data\OrderInterface')->load($_order_id);
        return $order;

     }
}
