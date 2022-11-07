<?php
/**
 * Copyright © Vbhex, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Vbhex\Checkout\Block\Account;

use \Magento\Framework\App\ObjectManager;
use \Vbhex\Checkout\Model\ResourceModel\Order\CollectionFactory;

/**
 * Buyer Transactions block
 *
 * @api
 * @since 100.0.2
 */
class BuyerTrxs extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Vbhex\Checkout\Model\ResourceModel\Order\CollectionFactory
     */
    protected $_orderCollectionFactory;

    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Order\Collection
     */
    protected $orders;

    /**
     * @var CollectionFactory
     */
    private $orderCollectionFactory;

    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Vbhex\Checkout\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory
     * @param \Magento\Customer\Model\Session $customerSession
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Vbhex\Checkout\Model\ResourceModel\Order\CollectionFactory $orderCollectionFactory,
        \Magento\Customer\Model\Session $customerSession,
        array $data = []
    ) {
        $this->_orderCollectionFactory = $orderCollectionFactory;
        $this->_customerSession = $customerSession;
        parent::__construct($context, $data);
    }

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('Buyer Transactions'));
    }

    /**
     * Provide order collection factory
     *
     * @return CollectionFactoryInterface
     * @deprecated 100.1.1
     */
    private function getOrderCollectionFactory()
    {
        if ($this->orderCollectionFactory === null) {
            $this->orderCollectionFactory = ObjectManager::getInstance()->get(CollectionFactory::class);
        }
        return $this->orderCollectionFactory;
    }

    /**
     * Get buyer orders
     *
     * @return bool|\Vbhex\Checkout\Model\ResourceModel\Order\Collection
     */
    public function getBuyerOrders()
    {
        if (!($customerId = $this->_customerSession->getCustomerId())) {
            return false;
        }
        if (!$this->orders) {
            $this->orders = $this->getOrderCollectionFactory()->create()->addFieldToSelect(
                '*'
            )->addFieldToFilter(
                'buyer_id', $customerId
            )->setOrder(
                'entity_id',
                'desc'
            );
        }
        return $this->orders;
    }

    public function getStatusLabel($status)
    {
        switch($status) {
            case 0 :
                return "Pending";
                break;
            case 1 :
                return "Paid";
                break;
            case 2 :
                return "Complete";
                break;
            default :
                return "Pending";
                break;
        }
    }

    /**
     * @inheritDoc
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getBuyerOrders()) {
            $pager = $this->getLayout()->createBlock(
                \Magento\Theme\Block\Html\Pager::class,
                'vbhexcheckout.account.buyertrxs.pager'
            )->setCollection(
                $this->getBuyerOrders()
            );
            $this->setChild('pager', $pager);
            $this->getBuyerOrders()->load();
        }
        return $this;
    }

    /**
     * Get Pager child block output
     *
     * @return string
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }

    /**
     * Get trx view URL
     *
     * @param object $order
     * @return string
     */
    public function getTrxUrl($order)
    {
        return $this->getUrl('vbhexcheckout/transaction/Buy', ['order' => $order->getOrderId()]);
    }

    /**
     * Get order view URL
     *
     * @param object $order
     * @return string
     */
    public function getOrderUrl($order)
    {
        return $this->getUrl('sales/order/view', ['order_id' => $order->getOrderId()]);
    }

    /**
     * Get order track URL
     *
     * @param object $order
     * @return string
     * @deprecated 102.0.3 Action does not exist
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function getTrackUrl($order)
    {
        //phpcs:ignore Magento2.Functions.DiscouragedFunction
        trigger_error('Method is deprecated', E_USER_DEPRECATED);
        return '';
    }

    /**
     * Get reorder URL
     *
     * @param object $order
     * @return string
     */
    public function getReorderUrl($order)
    {
        return $this->getUrl('sales/order/reorder', ['order' => $order->getOrderId()]);
    }

    /**
     * Get customer account URL
     *
     * @return string
     */
    public function getBackUrl()
    {
        return $this->getUrl('customer/account/');
    }

    /**
     * Get message for no orders.
     *
     * @return \Magento\Framework\Phrase
     * @since 102.1.0
     */
    public function getEmptyOrdersMessage()
    {
        return __('You have placed no orders.');
    }
}
