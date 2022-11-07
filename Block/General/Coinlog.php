<?php

namespace Vbhex\Checkout\Block\General;

use \Magento\Framework\App\ObjectManager;
use \Vbhex\Checkout\Model\ResourceModel\CoinLog\CollectionFactory;

class Coinlog extends \Magento\Framework\View\Element\Template
{
   protected $_coinLog;
   protected $_coinLogCollectionFactory;
   protected $_scopeConfig;
   protected $_order_id;
   protected $_coin_id;
   protected $_wallet;

   /**      * @param \Magento\Framework\App\Action\Context $context      */
   public function __construct(
      \Magento\Framework\View\Element\Template\Context $context,
      \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
      \Vbhex\Checkout\Model\ResourceModel\CoinLog\CollectionFactory $coinLogCollectionFactory
   ){
      $this->_coinLogCollectionFactory     = $coinLogCollectionFactory;
      $this->_scopeConfig = $scopeConfig;
      $this->_order_id = $this->getData('order_id');
      $this->_coin_id = $this->getData('coin_id');
      $this->_wallet = $this->getData('wallet');
      parent::__construct($context);
   }

   public function getStoreConfig($_env){

        $_val = $this->_scopeConfig->getValue($_env, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $_val;
    }

   /**
     * Provide coin log collection factory
     *
     * @return CollectionFactoryInterface
     * @deprecated 100.1.1
     */
    private function getCoinLogCollectionFactory()
    {
        if ($this->_coinLogCollectionFactory === null) {
            $this->_coinLogCollectionFactory = ObjectManager::getInstance()->get(CollectionFactory::class);
        }
        return $this->_coinLogCollectionFactory;
    }

    public function getLogs()
    {
        $order_id = $this->getRequest()->getParam('order');
        $coin_id = $this->getRequest()->getParam('coin');
        $wallet = $this->getRequest()->getParam('wallet');

        if(!$this->_coinLog)
        {
            $collection = $this->getCoinLogCollectionFactory()->create()->addFieldToSelect('*');
            if (!empty($order_id)) {
                $collection->addFieldToFilter('app_order_id', $order_id);
            }

            if (!empty($coin_id)) {
                $collection->addFieldToFilter('coin_id', $coin_id);
            }

            if (!empty($wallet)) {
                $collection->addFieldToFilter('user_wallet', $wallet);
            }

            $collection->setOrder(
                'entity_id',
                'desc'
            );
            $this->_coinLog = $collection;
        }
        return $this->_coinLog;
    }

    /**
     * @inheritDoc
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        if ($this->getLogs())
        {
            $pager = $this->getLayout()->createBlock(
                \Magento\Theme\Block\Html\Pager::class,
                'vc.general.coinlog.pager'
            )->setCollection(
                $this->getLogs()
            );
            $this->setChild('pager', $pager);
            $this->getLogs()->load();
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

    public function hideTxHash($hash)
    {
        $first = substr($hash, 0, 5);
        $end  =  substr($hash, -5);

        return $first."****".$end;
    }

    public function getScenariosLabel($status)
    {
        switch ($status) {
            case 1:
                return "Pay Order";
                break;
            case 2:
                return "Vote";
                break;
            case 3:
                return "Income";
                break;
            case 4:
                return "Outgo";
                break;
            case 5:
                return "Withdraw";
                break;
            case 6:
                return "Buy Coin";
                break;
            default:
                return "Unknow";
                break;
        }
    }

    /**
     * Get message for no logs.
     *
     * @return \Magento\Framework\Phrase
     * @since 102.1.0
     */
    public function getEmptyLogsMessage()
    {
        return __('You have no logs.');
    }
}
