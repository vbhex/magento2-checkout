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

namespace Vbhex\Checkout\Model;

use Magento\Framework\Model\AbstractModel;
use Vbhex\Checkout\Api\Data\OrderInterface;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * VbhexCheckout Order Model.
 *
 * @method \Vbhex\CheckoutModel\ResourceModel\Order _getResource()
 * @method \Vbhex\Checkout\Model\ResourceModel\Order getResource()
 */
class Order extends AbstractModel implements OrderInterface, IdentityInterface
{
    /**
     * No route page id.
     */
    const NOROUTE_ENTITY_ID = 'no-route';

    /**#@+
     * Order's Statuses
     */
    const STATUS_ENABLED    = 1;
    const STATUS_PENDING    = 0;
    const STATUS_PROCESSING = 2;
    const STATUS_DISABLED   = 3;
    /**#@-*/

    /**
     * VbhexCheckout Order cache tag.
     */
    const CACHE_TAG = 'vc_order';

    /**
     * @var string
     */
    protected $_cacheTag = 'vc_order';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'vc_order';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init(
            \Vbhex\Checkout\Model\ResourceModel\Order::class
        );
    }

    /**
     * Load object data.
     *
     * @param int|null $id
     * @param string   $field
     *
     * @return $this
     */
    public function load($id, $field = null)
    {
        if ($id === null) {
            return $this->noRouteOrder();
        }

        return parent::load($id, $field);
    }

    /**
     * Load No-Route Order.
     *
     * @return \Webkul\Marketplace\Model\Order
     */
    public function noRouteOrder()
    {
        return $this->load(self::NOROUTE_ENTITY_ID, $this->getIdFieldName());
    }

    /**
     * Get identities.
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG.'_'.$this->getId()];
    }

    /**
     * Get ID.
     *
     * @return int
     */
    public function getId()
    {
        return parent::getData(self::ENTITY_ID);
    }

    /**
     * Set ID.
     *
     * @param int $id
     *
     * @return \Vbhex\Checkout\Api\Data\OrderInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }

    /**
     * Update VbhexCheckout Order.
     *
     * @param int $id
     * @param int $order_id
     * @param int $block
     * @param array $customer
     * @return string
     */
    public function updateOrder($id, $order_id, $block, $customer)
    {
        $data = array();
        $data['entity_id']  =   $id;
        $data['order_id']   =   $order_id;
        $data['block']      =   $block;
        $data['customer']   =   $customer;
        // $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        // $orders = $objectManager->create('\Magento\Sales\Model\Order')->load($order_id);
        // $orderState = $this->getStoreConfig('payment/vbhex_checkout/order_status');
        // $orders->setStatus($orderState);
        // $orders->save();
        echo json_encode($data,true);
        exit;
    }


}
