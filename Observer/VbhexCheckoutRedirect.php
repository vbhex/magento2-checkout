<?php
namespace Vbhex\Checkout\Observer;

use Magento\Framework\Event\ObserverInterface;
class VbhexCheckoutRedirect implements ObserverInterface
{
    protected $_redirect;
    protected $_response;
    protected $_scopeConfig;
    protected $request;
    protected $_messageManager;
    protected $_url;
    protected $_logger;
    protected $ordersFactory;
    protected $helper;

    public function __construct(
        \Psr\Log\LoggerInterface $logger,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Framework\UrlInterface $url,
        \Magento\Framework\App\Response\RedirectInterface $redirect,
        \Magento\Framework\App\ResponseInterface $response,
        \Magento\Framework\App\RequestInterface $request,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Catalog\Api\ProductRepositoryInterface $productRepository,
        \Magento\Sales\Model\Service\InvoiceService $invoiceService,
        \Webkul\Marketplace\Model\OrdersFactory $ordersFactory,
        \Vbhex\Checkout\Helper\Data $vc_helper
    ) {
         $this->_logger = $logger;
         $this->_redirect = $redirect;
         $this->_response = $response;
         $this->_url = $url;
         $this->_scopeConfig = $scopeConfig;
         $this->request = $request;
         $this->_messageManager = $messageManager;
         $this->productRepository = $productRepository;
         $this->_invoiceService = $invoiceService;
         $this->ordersFactory = $ordersFactory;
         $this->helper = $vc_helper;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
      $order_ids = $observer->getEvent()->getOrderIds();
      $order_id = $order_ids[0];
      $order = $this->getOrder($order_id);

      if ($order->getPayment()->getMethodInstance()->getCode() == 'vbhex_checkout') {

        //try to get seller wallet address
        $sellerOrder = $this->ordersFactory->create()
        ->getCollection()
        ->addFieldToFilter('order_id', $order_id)
        ->addFieldToFilter('seller_id', ['neq' => 0]);
        if(count($sellerOrder) == 1) {
            $buyer_id = empty($this->helper->getCustomerId())? 0 : $this->helper->getCustomerId();
            $seller_id = 0;
            foreach($sellerOrder as $info) {
                $seller_id = $info['seller_id'];
            }
            $partner = $this->helper->getVbhexCheckoutSettingsBySellerId($seller_id);
            $seller_address = $partner["eth_wallet_address"] ?? "";


         $selectcoin = $order->getPayment()->getAdditionalInformation('selected_coin');
         $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
         $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
         $connection = $resource->getConnection();
         $sql = "SELECT * FROM vc_order WHERE order_id= '".$order_id."'";
         $result = $connection->fetchAll($sql);
         $this->_logger->info("vbhex_checkout_log selected coin : ".$selectcoin);

         if(!$result){

            $sql = "SELECT * FROM vc_coins WHERE entity_id = '".$selectcoin."'";
            $result = $connection->fetchAll($sql);

            if (!empty($result)) {
               $wallet =$result[0];

               if(isset($wallet['price'])){

                  $currencyCode = $order->getOrderCurrencyCode();
                  $coin_price   = $wallet['price'] ?? 0;
                  $coin_symbol  = $wallet['symbol'] ?? "";
                  $coin_address = $wallet['contract_address'] ?? "0x0000000000000000000000000000000000000000";
                  $coin_decm    = $wallet['decm'] ?? 0;
                  $grandTotal   = $order->getGrandTotal();
                  $invoice_exchange_rate = $this->getStoreConfig('payment/vbhex_checkout/ex_rate');
                  $app_id = $this->getStoreConfig('payment/vbhex_checkout/app_id');
                  if($invoice_exchange_rate == 0 || $invoice_exchange_rate == ''){
                     $invoice_exchange_rate = 1;
                  }
                  $amount = bcmul($grandTotal,$invoice_exchange_rate,2);
                  $coin_amount = bcdiv($amount,$coin_price,6);
                  $db_order = [
                        'app_id'            =>  $app_id,
                        'order_id'          =>  $order_id,
                        'seller_id'         =>  $seller_id,
                        'buyer_id'          =>  $buyer_id,
                        'seller_wallet'     =>  $seller_address,
                        'fiat_amount'       =>  $amount,
                        'fiat_symbol'       =>  $currencyCode,
                        'selected_coin'     =>  $selectcoin,
                        'coin_symbol'       =>  $coin_symbol,
                        'coin_decm'         =>  intval($coin_decm),
                        'coin_address'      =>  $coin_address,
                        'coin_amount'       =>  $coin_amount,
                        'coin_price'        =>  $coin_price,
                        'order_id'          =>  $order_id
                  ];
                  $this->dataInsertDB('vc_order',$db_order);
                  $redirectUrl = $this->getBaseUrl().'vc/transaction/Buy/order/'.$order_id;
                  return $this->_redirect->redirect($this->_response, $redirectUrl);
               } else {
                  $msg="Get invalid coin";
               }
            } else {
               $msg="No coin Found";
            }
            //delete order
            $registry = $objectManager->get('Magento\Framework\Registry');
            $registry->register('isSecureArea','true');
            $order->delete();
            $registry->unregister('isSecureArea');

            if(!isset($msg)){
               $msg = 'Something went wrong';
            }
            $this->_messageManager->addError($msg);
            $cartUrl = $this->_url->getUrl('checkout/cart/index');
            $this->_redirect->redirect($this->_response, $cartUrl);
         } else {
            $status = $result[0]['payment_status'];
            if($status == 1 || $status == 3){
               $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
               $orders = $objectManager->create('\Magento\Sales\Model\Order')->load($order_id);
               $orderState = $this->getStoreConfig('payment/vbhex_checkout/order_status');
               $orders->setStatus($orderState);
               $orders->save();
               $this->downloadableOrder($order_id);
            }
         }
        } else{
            $msg = "You can only make orders for one seller if you choose VbhexCheckout payment";
            $this->_messageManager->addError($msg);
            $cartUrl = $this->_url->getUrl('checkout/cart/index');
            $this->_redirect->redirect($this->_response, $cartUrl);
        }
      }
    } //end execute function

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

   public function getStoreConfig($_env){

      $_val = $this->_scopeConfig->getValue($_env, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
      return $_val;
   }

   public function dataInsertDB($_table, $_data){

      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
      $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
      $connection = $resource->getConnection();
      $connection->insertForce($_table,$_data);
   }

   public function downloadableOrder($orderId)
   {
      $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
      $order = $objectManager->create('Magento\Sales\Api\Data\OrderInterface')->load($orderId);
      $orderItems = $order->getAllItems();
      $isDownloadableType = null;
      foreach ($orderItems as $item) {
         $product = $this->productRepository->get($item->getSku());
         if ($product->getTypeId() === \Magento\Downloadable\Model\Product\Type::TYPE_DOWNLOADABLE) {
            $isDownloadableType = true;
            break;
         }
      }
      if($isDownloadableType){
         $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
         $connection = $resource->getConnection();
         $status = \Magento\Downloadable\Model\Link\Purchased\Item::LINK_STATUS_AVAILABLE;
         $sql = "UPDATE downloadable_link_purchased_item SET status='$status' WHERE purchased_id IN (SELECT purchased_id FROM downloadable_link_purchased WHERE order_id=$orderId)";
         $result = $connection->query($sql);
      }
   }

}
