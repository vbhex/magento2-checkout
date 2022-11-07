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
namespace Vbhex\Checkout\Api\Data;

/**
 * Dservice Coin interface.
 * @api
 */
interface CoinInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ENTITY_ID             =   'entity_id';

    const NAME                  =   'name';

    const SYMBOL                =   'symbol';

    const DECM                  =   'decm';

    const CONTRACT_ADDRESS      =   'contract_address';

    const PRICE                 =   'price';

    const LOGO                  =   'logo';

    const IS_VALID              =   'is_valid';

    const IS_CURRENCY           =   'is_currency';

    const SYNC_PRICE            =   'sync_price';

    const CREATED_AT            =   'created_at';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get Name
     *
     * @return string|null
     */
    public function getName();

    /**
     * Get Symbol
     *
     * @return string|null
     */
    public function getSymbol();

    /**
     * Get Decm
     *
     * @return int|null
     */
    public function getDecm();

    /**
     * Get Contract Address
     *
     * @return int|null
     */
    public function getContractAddress();

    /**
     * Get Price
     *
     * @return decimal|null
     */
    public function getPrice();

    /**
     * Get Coin Logo
     *
     * @return int|null
     */
    public function getLogo();

    /**
     * Get Is Valid
     *
     * @return boolean|null
     */
    public function getIsValid();

    /**
     * Get Is Currency
     *
     * @return boolean|null
     */
    public function getIsCurrency();

    /**
     * Get Sync Price
     *
     * @return boolean|null
     */
    public function getSyncPrice();

    /**
     * Get Created At
     *
     * @return date|null
     */
    public function getCreatedAt();

    /**
     * Set ID
     *
     * @param int $id
     * @return \Vbhex\Checkout\Api\Data\CoinInterface
     */
    public function setId($id);
}
