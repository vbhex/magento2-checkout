<?php
/**
 * Copyright © Vbhex, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Vbhex\Checkout\Block\Dispute;

use \Magento\Framework\App\ObjectManager;
use \Vbhex\Checkout\Model\ResourceModel\Dispute\CollectionFactory;

/**
 * General Dispute Lists block
 *
 * @api
 * @since 100.0.2
 */
class Lists extends \Magento\Framework\View\Element\Template
{

    /**
     * @var \Vbhex\Checkout\Model\ResourceModel\Dispute\CollectionFactory
     */
    protected $_disputeCollectionFactory;

    /**
     * @var \Magento\Sales\Model\ResourceModel\Dispute\Collection
     */
    protected $disputes;


    /**
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param \Vbhex\Checkout\Model\ResourceModel\Dispute\CollectionFactory $disputeCollectionFactory
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Vbhex\Checkout\Model\ResourceModel\Dispute\CollectionFactory $disputeCollectionFactory,
        array $data = []
    ) {
        $this->_disputeCollectionFactory = $disputeCollectionFactory;
        parent::__construct($context, $data);
    }

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        parent::_construct();
        $this->pageConfig->getTitle()->set(__('Dispute List'));
    }

    /**
     * Provide dispute collection factory
     *
     * @return CollectionFactoryInterface
     * @deprecated 100.1.1
     */
    private function getDisputeCollectionFactory()
    {
        if ($this->_disputeCollectionFactory === null) {
            $this->_disputeCollectionFactory = ObjectManager::getInstance()->get(CollectionFactory::class);
        }
        return $this->_disputeCollectionFactory;
    }

    /**
     * Get Dispute List
     *
     * @return bool|\Vbhex\Checkout\Model\ResourceModel\Dispute\Collection
     */
    public function getList()
    {
        $status = $this->getRequest()->getParam('status');

        if(!$this->disputes)
        {
            $collection = $this->getDisputeCollectionFactory()->create()->addFieldToSelect('*');
            if (!empty($status)) {
                $collection->addFieldToFilter('main_table.status', $status);
            }
            $collection->join(
                ['order' => 'vbhexcheckout_order'],
                'order.entity_id = main_table.vbhexcheckout_order_id'
            );
            $collection->setOrder(
                'entity_id',
                'desc'
            );
            $this->disputes = $collection;
        }
        return $this->disputes;
    }

    public function getStatusLabel($status)
    {
        switch($status) {
            case 0:
                return "Refund Not Asked";
                break;
            case 1:
                return "Refund Asked";
                break;
            case 2:
                return "Refund Permitted By Voters";
                break;
            case 3:
                return "Refund Permitted By Seller";
                break;
            case 4:
                return "Refund Denied By Voters";
                break;
            case 5:
                return "Refund Refused By Seller";
                break;
            case 6:
                return "Seller Not Responsed In Time & Buyer Cash Out";
                break;
            case 7:
                return "In Appealing (Voting)";
                break;
            default:
                return "Refund Not Asked";
                break;
        }
    }

    /**
     * @inheritDoc
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getList()) {
            $pager = $this->getLayout()->createBlock(
                \Magento\Theme\Block\Html\Pager::class,
                'vbhexcheckout.dispute.lists.pager'
            )->setCollection(
                $this->getList()
            );
            $this->setChild('pager', $pager);
            $this->getList()->load();
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
     * @param object $orderId
     * @return string
     */
    public function getTrxUrl($orderId)
    {
        return $this->getUrl('vbhexcheckout/dispute/detail', ['order' => $orderId]);
    }

    /**
     * Get message for no disputes.
     *
     * @return \Magento\Framework\Phrase
     * @since 102.1.0
     */
    public function getEmptyDisputesMessage()
    {
        return __('There is no disputes.');
    }
}
