<?php
/**
 * Vbhex Software.
 *
 * @category  Vbhex
 * @package   Vbhex_Checkout
 * @author    Vbhex
 * @copyright Copyright (c) Vbhex Software Private Limited (https://vbhex.com)
 * @license   https://store.vbhex.com/license.html
 */

namespace Vbhex\Checkout\Helper;

use Magento\Eav\Model\ResourceModel\Entity\Attribute\Set\CollectionFactory;
use Magento\Framework\App\Http\Context as HttpContext;
use Magento\Customer\Model\Context as CustomerContext;
use Magento\Store\Model\ScopeInterface;
use Vbhex\Checkout\Model\ResourceModel\Seller\CollectionFactory as SellerCollection;
use Magento\Catalog\Model\Product\Visibility;
use Magento\UrlRewrite\Model\UrlRewriteFactory;
use Zend\Log\Writer\Stream;
use Zend\Log\Logger;

/**
 * Vbhex Checkout Helper Data.
 */
class Data extends \Magento\Framework\App\Helper\AbstractHelper
{

    /**
     * @var ObjectManagerInterface
     */
    protected $_objectManager;

    /**
     * @var \Magento\Customer\Model\SessionFactory
     */
    protected $_customerSessionFactory;

    /**
     * @var null|array
     */
    protected $_options;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $_storeManager;

    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product
     */
    protected $_product;

    /**
     * @var CollectionFactory
     */
    protected $_collectionFactory;

    /**
     * @var HttpContext
     */
    private $httpContext;

    /**
     * @var \Vbhex\Checkout\Model\Seller
     */
    protected $vcSeller;

    /**
     * @var \Magento\Framework\App\Cache\ManagerFactory
     */
    protected $cacheManager;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $customerModel;

    /**
     * @var Visibility
     */
    protected $visibility;

    /**
     * @var ControllersRepository
     */
    protected $controllersRepository;

    /**
     * @var UrlRewriteFactory
     */
    protected $urlRewriteFactory;

    protected $customerSession;

    /**
     * @var \Vbhex\Checkout\Logger\Logger
     */
    protected $logger;
    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param \Magento\Customer\Model\SessionFactory $customerSessionFactory
     * @param CollectionFactory $collectionFactory
     * @param HttpContext $httpContext
     * @param \Magento\Catalog\Model\ResourceModel\Product $product
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Directory\Model\Currency $currency
     * @param \Magento\Framework\Locale\CurrencyInterface $localeCurrency
     * @param \Magento\Framework\App\Cache\ManagerFactory $cacheManagerFactory
     * @param \Magento\Framework\View\Element\BlockFactory $blockFactory
     * @param SellerCollection $sellerCollectionFactory
     * @param \Magento\Framework\App\ResourceConnection $resource
     * @param \Vbhex\Checkout\Model\Seller $vcSeller
     * @param \Magento\Framework\Locale\FormatInterface $localeFormat
     * @param \Magento\Customer\Model\CustomerFactory $customerModel
     * @param Visibility $visibility
     * @param ControllersRepository $controllersRepository
     * @param UrlRewriteFactory $urlRewriteFactory
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        \Magento\Customer\Model\SessionFactory $customerSessionFactory,
        CollectionFactory $collectionFactory,
        HttpContext $httpContext,
        \Magento\Catalog\Model\ResourceModel\Product $product,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Directory\Model\Currency $currency,
        \Magento\Framework\Locale\CurrencyInterface $localeCurrency,
        \Magento\Framework\App\Cache\ManagerFactory $cacheManagerFactory,
        \Magento\Framework\View\Element\BlockFactory $blockFactory,
        \Webkul\Marketplace\Model\ResourceModel\Seller\CollectionFactory $sellerCollectionFactory,
        \Magento\Framework\App\ResourceConnection $resource,
        \Magento\Framework\Locale\FormatInterface $localeFormat,
        \Magento\Indexer\Model\IndexerFactory $indexerFactory,
        \Vbhex\Checkout\Model\SellerFactory $vcSeller,
        \Magento\Indexer\Model\Indexer\CollectionFactory $indexerCollectionFactory,
        \Magento\Customer\Model\CustomerFactory $customerModel,
        Visibility $visibility,
        UrlRewriteFactory $urlRewriteFactory,
        \Psr\Log\LoggerInterface $logger,
        \Magento\Customer\Model\Session $customerSession
    ) {
        $this->_objectManager = $objectManager;
        $this->_customerSessionFactory = $customerSessionFactory;
        $this->_collectionFactory = $collectionFactory;
        $this->httpContext = $httpContext;
        $this->_product = $product;
        parent::__construct($context);
        $this->_currency = $currency;
        $this->_localeCurrency = $localeCurrency;
        $this->_storeManager = $storeManager;
        $this->cacheManager = $cacheManagerFactory;
        $this->_blockFactory = $blockFactory;
        $this->_sellerCollectionFactory = $sellerCollectionFactory;
        $this->_resource = $resource;
        $this->_localeFormat = $localeFormat;
        $this->indexerFactory = $indexerFactory;
        $this->vcSeller = $vcSeller;
        $this->indexerCollectionFactory = $indexerCollectionFactory;
        $this->customerModel = $customerModel;
        $this->visibility = $visibility;
        $this->urlRewriteFactory = $urlRewriteFactory;
        $this->logger = $logger;
        $this->customerSession = $customerSession;
    }

    public function getVbhexCheckoutSettings()
    {
        $sellerId = $this->getCustomerId();
        $storeId = $this->getCurrentStoreId();
        $autoId = 0;
        $collection = $this->vcSeller->create()
                    ->getCollection()
                    ->addFieldToFilter('seller_id', $sellerId)
                    ->addFieldToFilter('store_id', $storeId);
        foreach ($collection as $value) {
            $autoId = $value->getId();
        }
        $vcSeller =   $this->vcSeller->create()->load($autoId);
        return $vcSeller;
    }

    public function getVbhexCheckoutSettingsBySellerId($sellerId)
    {
        $storeId = $this->getCurrentStoreId();
        $autoId = 0;
        $collection = $this->vcSeller->create()
                    ->getCollection()
                    ->addFieldToFilter('seller_id', $sellerId)
                    ->addFieldToFilter('store_id', $storeId);
        foreach ($collection as $value) {
            $autoId = $value->getId();
        }
        $vcSeller =   $this->vcSeller->create()->load($autoId);
        return $vcSeller;
    }

    /**
     * Return Customer id.
     *
     * @return bool|0|1
     */
    public function getCustomer()
    {
        return $this->_customerSessionFactory->create()->getCustomer();
    }

    /**
     * Return Customer Data
     *
     * @return /Magento/Customer/Model/Customer
     */
    public function getCustomerData()
    {
        $customerId = $this->getCustomerId();
        $customerModel = $this->customerModel->create();
        $customerModel->load($customerId);
        return $customerModel;
    }

    /**
     * Return Customer id.
     *
     * @return bool|0|1
     */
    public function getCustomerId()
    {
        // return $this->httpContext->getValue('customer_id');
        return $this->customerSession->getCustomer()->getId();
    }

    /**
     * Check if customer is logged in
     *
     * @return bool
     * @codeCoverageIgnore
     */
    public function isCustomerLoggedIn()
    {
        return (bool)$this->httpContext->getValue(CustomerContext::CONTEXT_AUTH);
    }

    /**
     * Return the Customer seller status.
     *
     * @return bool|0|1
     */
    public function isSeller()
    {
        $sellerStatus = 0;
        $sellerId = $this->getCustomerId();
        $model = $this->getSellerCollectionObj($sellerId);
        foreach ($model as $value) {
            if ($value->getIsSeller() == 1) {
                $sellerStatus = $value->getIsSeller();
            }
        }

        return $sellerStatus;
    }

    /**
     * Return the seller Data.
     *
     * @return \Vbhex\Checkout\Model\ResourceModel\Seller\Collection
     */
    public function getSellerData()
    {
        $sellerId = $this->getCustomerId();
        $model = $this->getSellerCollectionObj($sellerId);
        return $model;
    }


    /**
     * Return the seller data by seller id.
     *
     * @return \Vbhex\Checkout\Model\ResourceModel\Seller\Collection
     */
    public function getSellerDataBySellerId($sellerId = '')
    {
        $collection = $this->getSellerCollectionObj($sellerId);
        $collection = $this->joinCustomer($collection);

        return $collection;
    }

    /**
     * Join with Customer Collection
     *
     * @param object $collection
     *
     * @return object
     */
    public function joinCustomer($collection)
    {
        try {
            $collection->joinCustomer();
            if ($this->getCustomerSharePerWebsite()) {
                $websiteId = $this->getWebsiteId();
                $collection->addFieldToFilter('website_id', $websiteId);
            }
        } catch (\Exception $e) {
            $this->logDataInLogger("Helper_Data joinCustomer : ".$e->getMessage());
            return $collection;
        }

        return $collection;
    }

    /**
     * [logDataInLogger  is used to log the data in the marktplace.log file]
     * @param  string $data
     * @return
     */
    public function logDataInLogger($data)
    {
        $this->logger->info($data);
    }

    public function getCustomerSharePerWebsite()
    {
        return $this->scopeConfig->getValue(
            'customer/account_share/scope',
            ScopeInterface::SCOPE_STORE
        );
    }

    public function getAllStores()
    {
        return $this->_storeManager->getStores();
    }

    public function getCurrentStoreId()
    {
        // give the current store id
        return $this->_storeManager->getStore()->getStoreId();
    }

    public function getWebsiteId()
    {
        // give the current store id
        return $this->_storeManager->getStore(true)->getWebsite()->getId();
    }

    public function getAllWebsites()
    {
        // give the current store id
        return $this->_storeManager->getWebsites();
    }

    public function getSingleStoreStatus()
    {
        return $this->_storeManager->hasSingleStore();
    }

    public function getSingleStoreModeStatus()
    {
        return $this->_storeManager->isSingleStoreMode();
    }

    public function setCurrentStore($storeId)
    {
        return $this->_storeManager->setCurrentStore($storeId);
    }

    /**
     * Return the Seller existing status.
     *
     * @return \Vbhex\Checkout\Model\ResourceModel\Seller\Collection
     */
    public function isSellerExist()
    {
        $sellerId = $this->getCustomerId();
        $model = $this->getSellerCollectionObj($sellerId);
        return $model->getSize();
    }

    /**
     * Return the Seller data by customer Id stored in the session.
     *
     * @return \Vbhex\Checkout\Model\ResourceModel\Seller\Collection
     */
    public function getSeller()
    {
        $data = [];
        $sellerId = $this->getCustomerId();
        $model = $this->getSellerCollectionObj($sellerId);
        $customer = $this->customerModel->create()->load($this->getCustomerId());
        foreach ($model as $value) {
            $data = $value->getData();
        }

        return $data;
    }

    /**
     * Return the Seller Model Collection Object.
     *
     * @return \Vbhex\Checkout\Model\ResourceModel\Seller\Collection
     */
    public function getSellerCollectionObj($sellerId)
    {
        $collection = $this->getSellerCollection();
        $collection->addFieldToFilter('seller_id', $sellerId);
        $collection->addFieldToFilter('store_id', $this->getCurrentStoreId());
        // If seller data doesn't exist for current store

        if (!$collection->getSize()) {
            $collection = $this->getSellerCollection();
            $collection->addFieldToFilter('seller_id', $sellerId);
            $collection->addFieldToFilter('store_id', 0);
        }

        return $collection;
    }

    /**
     * Get Seller Collection
     *
     * @return \Vbhex\Checkout\Model\ResourceModel\Seller\Collection
     */
    public function getSellerCollection()
    {
        return $this->_sellerCollectionFactory->create();
    }

    /**
     * getSellerStatus return the seller status
     * @param  integer $sellerId [Seller Id]
     * @return string [Seller Status]
     */
    public function getSellerStatus($sellerId = 0)
    {
        $sellerStatus = 0;
        if (!$sellerId) {
            $sellerId = $this->getCustomerId();
        }
        $model = $this->getSellerCollectionObj($sellerId);
        foreach ($model as $value) {
            $sellerStatus = $value->getIsSeller();
        }
        return $sellerStatus;
    }

    /**
     * [getAllowedSellerStatus returns all seller's status array]
     * @return mixed
     */
    public function getAllowedSellerStatus()
    {
        $availableOptions = $this->marketplaceSeller->getAvailableStatuses();
        return $availableOptions;
    }

    /**
     * Return the Seller Model Collection Object.
     *
     * @return \Webkul\Marketplace\Model\ResourceModel\Seller\Collection
     */
    public function getSellerCollectionObjByShop($shopUrl)
    {
        $collection = $this->getSellerCollection();
        $collection->addFieldToFilter('is_seller', 1);
        $collection->addFieldToFilter('shop_url', $shopUrl);
        $collection->addFieldToFilter('store_id', $this->getCurrentStoreId());
        // If seller data doesn't exist for current store
        if (!$collection->getSize()) {
            $collection = $this->getSellerCollection();
            $collection->addFieldToFilter('is_seller', 1);
            $collection->addFieldToFilter('shop_url', $shopUrl);
            $collection->addFieldToFilter('store_id', 0);
        }

        return $collection;
    }

    /**
     * getSellerList is used to get the list of all the sellers
     * @return mixed
     */
    public function getSellerList()
    {
        $sellerCollection = $this->getSellerCollection();
        $sellerList[] = ['value' => '', 'label' => __('Please Select')];
        $customerGridFlat = $this->getSellerCollection()->getTable('customer_grid_flat');
        $sellerCollection->getSelect()->join(
            $customerGridFlat.' as cgf',
            'main_table.seller_id = cgf.entity_id',
            [
                'name'=>'name',
            ]
        )->where('main_table.store_id = 0 AND main_table.is_seller = 1');
        foreach ($sellerCollection as $item) {
            $sellerList[] = ['value' => $item->getSellerId(), 'label' => $item->getName()];
        }
        return $sellerList;
    }

    /**
     * Clean Cache
     */
    public function clearCache()
    {
        $cacheManager = $this->cacheManager->create();
        $availableTypes = $cacheManager->getAvailableTypes();
        $cacheManager->clean($availableTypes);
    }



    public function validateXssString($value = null)
    {
        $notAllowedEvents = [
            // Mouse Event Attributes
            'onclick',
            'ondblclick',
            'onmousedown',
            'onmousemove',
            'onmouseout',
            'onmouseover',
            'onmouseup',
            'onmousewheel',
            'onwheel',
            // Window Event Attributes
            'onafterprint',
            'onbeforeprint',
            'onbeforeunload',
            'onerror',
            'onhashchange',
            'onload',
            'onmessage',
            'onoffline',
            'ononline',
            'onpagehide',
            'onpageshow',
            'onpopstate',
            'onresize',
            'onstorage',
            'onunload',
            // Form Events
            'onblur',
            'onchange',
            'oncontextmenu',
            'onfocus',
            'oninput',
            'oninvalid',
            'onreset',
            'onsearch',
            'onselect',
            'onsubmit',
            // Keyboard Events
            'onkeydown',
            'onkeypress',
            'onkeyup',
            // Drag Events
            'ondrag',
            'ondragend',
            'ondragenter',
            'ondragleave',
            'ondragover',
            'ondragstart',
            'ondrop',
            'onscroll',
            // Clipboard Events
            'oncopy',
            'oncut',
            'onpaste',
            // Media Events
            'onabort',
            'oncanplay',
            'oncanplaythrough',
            'oncuechange',
            'ondurationchange',
            'onemptied',
            'onended',
            'onloadeddata',
            'onloadedmetadata',
            'onloadstart',
            'onpause',
            'onplay',
            'onplaying',
            'onprogress',
            'onratechange',
            'onseeked',
            'onseeking',
            'onstalled',
            'onsuspend',
            'ontimeupdate',
            'onvolumechange',
            'onwaiting',
            // Misc Events
            'onshow',
            'ontoggle',
        ];
        foreach ($notAllowedEvents as $event) {
            $value = preg_replace("/".$event."=.*?/s", "", $value) ? : $value;
        }
        return $value;
    }
}
