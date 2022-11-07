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
use Vbhex\Checkout\Api\Data\CoinLogInterface;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * Dservice Coin Log Model.
 *
 * @method \Vbhex\CheckoutModel\ResourceModel\CoinLog _getResource()
 * @method \Vbhex\Checkout\Model\ResourceModel\CoinLog getResource()
 */
class CoinLog extends AbstractModel implements CoinLogInterface, IdentityInterface
{
    /**
     * No route page id.
     */
    const NOROUTE_ENTITY_ID = 'no-route';



    /**
     * Dservice Coin cache tag.
     */
    const CACHE_TAG = 'vbhexcheckout_coin_logs';

    /**
     * @var string
     */
    protected $_cacheTag = 'vbhexcheckout_coin_logs';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'vbhexcheckout_coin_logs';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init(
            \Vbhex\Checkout\Model\ResourceModel\CoinLog::class
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
     * Get User Wallet
     *
     * @return string|null
     */
    public function getUserWallet()
    {
        return parent::getData(self::USER_WALLET);
    }
    /**
     * Get Counterpart Wallet
     *
     * @return string|null
     */
    public function getCounterpartWallet()
    {
        return parent::getData(self::COUNTERPART_WALLET);
    }

    /**
     * Get Transaction Hash
     *
     * @return string|null
     */
    public function getTxHash()
    {
        return parent::getData(self::TX_HASH);
    }

    /**
     * Get Coin ID
     *
     * @return int|null
     */
    public function getCoinId()
    {
        return parent::getData(self::COIN_ID);
    }

    /**
     * Get Coin Symbol
     *
     * @return string|null
     */
    public function getCoinSymbol()
    {
        return parent::getData(self::COIN_SYMBOL);
    }

    /**
     * Get Coin Decm
     *
     * @return int|null
     */
    public function getCoinDecm()
    {
        return parent::getData(self::COIN_DECM);
    }

    /**
     * Get Coin Amount
     *
     * @return int|null
     */
    public function getCoinAmount()
    {
        return parent::getData(self::COIN_AMOUNT);
    }

    /**
     * Get Scenarios
     *
     * @return int|null
     */
    public function getScenarios()
    {
        return parent::getData(self::SCENARIOS);
    }

    /**
     * Get Diff Type
     *
     * @return int|null
     */
    public function getDiffType()
    {
        return parent::getData(self::DIFF_TYPE);
    }

    /**
     * Get App Id
     *
     * @return int|null
     */
    public function getAppId()
    {
        return parent::getData(self::APP_ID);
    }

    /**
     * Get Chain Order Id
     *
     * @return int|null
     */
    public function getChainOrderId()
    {
        return parent::getData(self::CHAIN_ORDER_ID);
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
