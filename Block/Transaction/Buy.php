<?php

namespace Vbhex\Checkout\Block\Transaction;

class Buy extends \Magento\Framework\View\Element\Template
{
   protected $request;
   protected $_scopeConfig;
   protected $_coinLogCollectionFactory;

   /**      * @param \Magento\Framework\App\Action\Context $context      */
   public function __construct(
      \Magento\Framework\View\Element\Template\Context $context,
      \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
      \Magento\Framework\App\Request\Http $request,
      \Vbhex\Checkout\Model\ResourceModel\CoinLog\CollectionFactory $coinLogCollectionFactory
   ){
      $this->request = $request;
      $this->_scopeConfig = $scopeConfig;
      $this->_coinLogCollectionFactory     = $coinLogCollectionFactory;
      parent::__construct($context);
   }

   public function getStoreConfig($_env){

        $_val = $this->_scopeConfig->getValue($_env, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $_val;
    }

   public function getDetail(){

      $order_id = $this->getData('order_id');
      $resultData = array();

      if($order_id){
         $order = $this->getOrder($order_id);
         $selectcoin = $order->getPayment()->getSelectedCoin();
         $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
         $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
         $connection = $resource->getConnection();
         $sql = "SELECT
         do.entity_id,
         do.seller_wallet,
         do.coin_amount,
         do.paid_amount,
         do.coin_symbol,
         do.coin_decm,
         do.coin_address,
         do.chain_order_id,
         do.tx_hash,
         do.status
         FROM vbhexcheckout_order as do WHERE do.order_id= '".$order_id."'";
         $result = $connection->fetchAll($sql);

         if (!empty($result)) {

         	$orderData = $result[0];

            // Get Order Information
            $resultData['app_id'] = $this->getStoreConfig('payment/vbhex_checkout/app_id');
            $resultData['vote_coin_decm'] = $this->getStoreConfig('payment/vbhex_checkout/vote_coin_decm');
            $resultData['order_id'] = $order_id;
            $resultData['entityID'] = $order->getEntityId();
            $resultData['incrementID'] = $order->getIncrementId();
            $resultData['state'] = $order->getState();
            $resultData['status'] = $order->getStatus();
            $resultData['storeID'] = $order->getStoreId();
            $resultData['grandTotal'] = $order->getGrandTotal();
            $resultData['subTotal'] = $order->getSubtotal();
            $resultData['shippingAmount'] = $order->getShippingAmount();
            $resultData['taxAmount'] = $order->getTaxAmount();
            $resultData['totalQtyOrdered'] = $order->getTotalQtyOrdered();
            $resultData['orderCurrencyCode'] = $order->getOrderCurrencyCode();
            $resultData['orderCurrencySymbol'] = $this->getCurrencySymbol($order->getOrderCurrencyCode());


            $resultData['entity_id'] = $orderData['entity_id'];
            $resultData['seller_wallet'] = $orderData['seller_wallet'];
            $resultData['totalAmount'] = $orderData['coin_amount'];
            $resultData['paidAmount'] = $orderData['paid_amount'];
            $resultData['coin'] = $selectcoin;
            $resultData['coin_symbol'] = $orderData['coin_symbol'];
            $resultData['coin_decm'] = $orderData['coin_decm'];
            $resultData['coin_address'] = $orderData['coin_address'];
            $resultData['tx_hash'] = $orderData['tx_hash'];
            $resultData['order_status'] = $orderData['status'];
            $resultData['chain_order_id'] = $orderData['chain_order_id'];
            // get order dispute information
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resource->getConnection();
            $sql_dispute = "SELECT
            dd.entity_id,
            dd.order_refund,
            dd.vote_price,
            dd.min_votes,
            dd.min_vote_diff,
            dd.refund_time,
            dd.agree,
            dd.disagree,
            dd.tx_hash,
            dd.status
            FROM vbhexcheckout_dispute as dd WHERE dd.chain_order_id= ".$orderData['chain_order_id']." AND dd.app_id = ".$resultData['app_id']." ";
            $result_dispute = $connection->fetchAll($sql_dispute);
            if(!empty($result_dispute)) {
                $orderDispute = $result_dispute[0];
                $resultData['refund'] = $orderDispute['order_refund'];
                $resultData['vote_price'] = $orderDispute['vote_price'];
                $resultData['min_votes'] = $orderDispute['min_votes'];
                $resultData['min_vote_diff'] = $orderDispute['min_vote_diff'];
                $resultData['refund_time'] = $orderDispute['refund_time'];
                $resultData['agree'] = $orderDispute['agree'];
                $resultData['disagree'] = $orderDispute['disagree'];
                $resultData['dispute_hash'] = $orderDispute['tx_hash'];
                $resultData['dispute_status'] = $orderDispute['status'];
                // get dispute discuss
                $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
                $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
                $connection = $resource->getConnection();
                $sql_dispute = "SELECT
        ddd.dispute_id,
        ddd.author_id,
        ddd.author_type,
        ddd.detail,
        ddd.created_at
        FROM vbhexcheckout_dispute_discuss as ddd WHERE ddd.dispute_id= " . $orderDispute['entity_id'];
                $result_dispute_discuss = $connection->fetchAll($sql_dispute);
                $resultData['dispute_discuss'] = $result_dispute_discuss;
            } else {
                $resultData['refund'] = 0;
                $resultData['vote_price'] = 0;
                $resultData['min_votes'] = 0;
                $resultData['min_vote_diff'] = 0;
                $resultData['refund_time'] = 0;
                $resultData['agree'] = 0;
                $resultData['disagree'] = 0;
                $resultData['dispute_hash'] = "No Transaction Yet";
                $resultData['dispute_status'] = 0;
            }
            // get customer details
            $resultData['customerFirstName'] = $order->getCustomerFirstname();
            $resultData['customerLastName'] = $order->getCustomerLastname();

            // get Coin Logs
            $collection = $this->_coinLogCollectionFactory->create()->addFieldToSelect('*');
            $collection->addFieldToFilter('app_order_id', $order_id);
            $collection->setOrder(
                'entity_id',
                'desc'
            );
            $collection->setPageSize(5);
            $resultData['coinLogs'] = $collection;
            // get shipping details
            $shippingAddress = $order->getShippingAddress();
            $resultData['show_shipping'] = true;
            if($shippingAddress !== null){
               $resultData['shippingFirstName'] = $shippingAddress->getFirstName();
               $resultData['shippingLastName'] = $shippingAddress->getLastName();
               $resultData['shippingCity'] = $shippingAddress->getCity();
               $resultData['shippingStreet'] = $shippingAddress->getStreet();
               $resultData['shippingPostcode'] = $shippingAddress->getPostcode();
               $resultData['shippingTelephone'] = $shippingAddress->getTelephone();
               $resultData['shippingState_code'] = $shippingAddress->getRegionCode();
               $resultData['shippingState_name'] = $this->getRegionName($shippingAddress->getRegionCode(), $shippingAddress->getCountryId());
               $resultData['shippingCountry_name'] = $this->getCountryName($shippingAddress->getCountryId());
            } else {
               $resultData['show_shipping'] = false;
               $resultData['shippingFirstName'] = "";
               $resultData['shippingLastName'] = "";
               $resultData['shippingCity'] = "";
               $resultData['shippingStreet'] = [];
               $resultData['shippingPostcode'] = "";
               $resultData['shippingTelephone'] = "";
               $resultData['shippingState_code'] = "";
               $resultData['shippingState_name'] = "";
               $resultData['shippingCountry_name'] = "";
            }

            // fetch specific payment information
            $resultData['amount'] = $order->getPayment()->getAmountPaid();
            $resultData['paymentMethod'] = $order->getPayment()->getMethod();
            $resultData['info'] = $order->getPayment()->getAdditionalInformation('method_title');

            // Get Order Items
            $orderItems = $order->getAllItems();
            $resultData['products'] = array();

            foreach ($orderItems as $item) {
               $data = array();
               $data['itemId'] = $item->getItemId();
               $data['orderId'] = $item->getOrderId();
               $data['storeId'] = $item->getStoreId();
               $data['productId'] = $item->getProductId();
               $data['productImage'] = $this->getProductImage($item->getProductId());
               $data['sku'] = $item->getSku();
               $data['productName'] = $item->getName();
               $data['productQty'] = (int)$item->getQtyOrdered();
               $data['productPrice'] = $item->getPrice();
               $options = "";
               if(isset($item->getProductOptions()['options'])){
                  $productOptions = $item->getProductOptions()['options'];
                  for ($i=0; $i < count($productOptions); $i++) {
                     if($i==0)
                        $options .= "(";
                     $options .= $productOptions[$i]['label'] . "-" . $productOptions[$i]['value'];
                     if($i+1 == count($productOptions))
                        $options .= ")";
                     else
                        $options .= ", ";
                  }
               }
               $data['productOptions'] = $options;
               array_push($resultData['products'], $data);
            }
            $resultData['flag'] = 1;
            $resultData['msg'] = "Success";

         } else {
            $resultData['flag'] = 0;
            $resultData['msg'] = "Error to Fetch Order Data";
         }

      } else {
         $resultData['flag'] = 0;
         $resultData['msg'] = "No order Found";
      }

      return $resultData;
   }

   public function getOrder($_order_id){

      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
      $order = $objectManager->create('Magento\Sales\Api\Data\OrderInterface')->load($_order_id);
      return $order;

   }

   public function getBaseUrl(){

      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
      $storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
      return $storeManager->getStore()->getBaseUrl();

    }

    /**
     * Get order coin log URL
     *
     * @param object $order
     * @return string
     */
    public function getLogUrl()
    {
        $order_id = $this->getData('order_id');
        return $this->getUrl('vbhexcheckout/general/coinlog', ['order' => $order_id]);
    }

   public function getRegionName($_region_code, $_country_id){

      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
      $regionName = $objectManager->create('Magento\Directory\Model\Region')->loadByCode($_region_code,$_country_id)->getName();
      return $regionName;

   }

   public function getCountryName($_country_id){

      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
      $countryName = $objectManager->get('Magento\Directory\Model\CountryFactory')->create()->loadByCode($_country_id)->getName();
      return $countryName;
   }

   public function getCurrencySymbol($_currency_code){

      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
      $currencySymbol = $objectManager->create('Magento\Directory\Model\CurrencyFactory')->create()->load($_currency_code)->getCurrencySymbol();
      return $currencySymbol;
   }

   public function getProductImage($_product_id){
      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
      $product = $objectManager->get('Magento\Catalog\Api\ProductRepositoryInterface')->getById($_product_id);
      $store = $objectManager->get('Magento\Store\Model\StoreManagerInterface')->getStore();
      $imageHelper = $objectManager->get(\Magento\Catalog\Helper\Image::class);

      $getSmallImage = $product->getSmallImage();

      if($getSmallImage != '' && $getSmallImage != null){
         $productImageUrl = $store->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'catalog/product' .$getSmallImage;
      }else{
         $productImageUrl = $imageHelper->getDefaultPlaceholderUrl('small_image');
      }

      return $productImageUrl;
   }

   public function simpleAddress($address) {
    $last4  =   substr($address,-4);
    $first4 =   substr($address,0,6);
    return $first4."***".$last4;
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
            default:
                return "Unknow";
                break;
        }
    }

}
