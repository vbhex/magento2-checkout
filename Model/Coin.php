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
use Vbhex\Checkout\Api\Data\CoinInterface;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * VbhexCheckout Coin Model.
 *
 * @method \Vbhex\CheckoutModel\ResourceModel\Coin _getResource()
 * @method \Vbhex\Checkout\Model\ResourceModel\Coin getResource()
 */
class Coin extends AbstractModel implements CoinInterface, IdentityInterface
{
    /**
     * No route page id.
     */
    const NOROUTE_ENTITY_ID = 'no-route';

    /**#@+
     * Coin's Statuses
     */
    const STATUS_ENABLED    = 1;
    const STATUS_PENDING    = 0;
    const STATUS_PROCESSING = 2;
    const STATUS_DISABLED   = 3;
    /**#@-*/

    /**
     * VbhexCheckout Coin cache tag.
     */
    const CACHE_TAG = 'vc_coins';

    /**
     * @var string
     */
    protected $_cacheTag = 'vc_coins';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'vc_coins';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init(
            \Vbhex\Checkout\Model\ResourceModel\Coin::class
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
            return $this->noRouteCoin();
        }

        return parent::load($id, $field);
    }

    /**
     * Load No-Route Coin.
     *
     * @return \Vbhex\Checkout\Model\Coin
     */
    public function noRouteCoin()
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
     * Get Name
     *
     * @return string|null
     */
    public function getName()
    {
        return parent::getData(self::NAME);
    }

    /**
     * Get Symbol
     *
     * @return string|null
     */
    public function getSymbol()
    {
        return parent::getData(self::SYMBOL);
    }

    /**
     * Get Decm
     *
     * @return int|null
     */
    public function getDecm()
    {
        return parent::getData(self::DECM);
    }

    /**
     * Get Coin Logo
     *
     * @return int|null
     */
    public function getLogo()
    {
        return parent::getData(self::LOGO);
    }

    /**
     * Get Price
     *
     * @return decimal|null
     */
    public function getPrice()
    {
        return parent::getData(self::PRICE);
    }

    /**
     * Get Coin Logo
     *
     * @return int|null
     */
    public function getContractAddress()
    {
        return parent::getData(self::CONTRACT_ADDRESS);
    }

    /**
     * Get Is Valid
     *
     * @return boolean|null
     */
    public function getIsValid()
    {
        return parent::getData(self::IS_VALID);
    }

    /**
     * Get Is Currency
     *
     * @return boolean|null
     */
    public function getIsCurrency()
    {
        return parent::getData(self::IS_CURRENCY);
    }

    /**
     * Get Sync Price
     *
     * @return boolean|null
     */
    public function getSyncPrice()
    {
        return parent::getData(self::SYNC_PRICE);
    }

    /**
     * Get Created At
     *
     * @return date|null
     */
    public function getCreatedAt()
    {
        return parent::getData(self::CREATED_AT);
    }

    /**
     * Set ID.
     *
     * @param int $id
     *
     * @return \Vbhex\Checkout\Api\Data\CoinInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }
}
