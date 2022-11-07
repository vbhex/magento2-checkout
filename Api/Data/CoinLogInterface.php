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
 * VbhexCheckout Coin Log interface.
 * @api
 */
interface CoinLogInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ENTITY_ID             =   'entity_id';

    const USER_WALLET           =   'user_wallet';

    const COUNTERPART_WALLET    =   'counterpart_wallet';

    const TX_HASH               =   'tx_hash';

    const COIN_ID               =   'coin_id';

    const COIN_SYMBOL           =   'coin_symbol';

    const COIN_DECM             =   'coin_decm';

    const COIN_AMOUNT           =   'coin_amount';

    const SCENARIOS             =   'scenarios';

    const DIFF_TYPE             =   'diff_type';

    const APP_ID                =   'app_id';

    const CHAIN_ORDER_ID        =   'chain_order_id';

    const CREATED_AT            =   'created_at';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get User Wallet
     *
     * @return string|null
     */
    public function getUserWallet();

    /**
     * Get Counterpart Wallet
     *
     * @return string|null
     */
    public function getCounterpartWallet();

    /**
     * Get Transaction Hash
     *
     * @return string|null
     */
    public function getTxHash();

    /**
     * Get Coin ID
     *
     * @return int|null
     */
    public function getCoinId();

    /**
     * Get Coin Symbol
     *
     * @return string|null
     */
    public function getCoinSymbol();

    /**
     * Get Coin Decm
     *
     * @return int|null
     */
    public function getCoinDecm();


    /**
     * Get Coin Amount
     *
     * @return int|null
     */
    public function getCoinAmount();

    /**
     * Get Scenarios
     *
     * @return int|null
     */
    public function getScenarios();

    /**
     * Get Diff Type
     *
     * @return int|null
     */
    public function getDiffType();

    /**
     * Get App Id
     *
     * @return int|null
     */
    public function getAppId();

    /**
     * Get Chain Order Id
     *
     * @return int|null
     */
    public function getChainOrderId();

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
     * @return \Vbhex\Checkout\Api\Data\CoinLogInterface
     */
    public function setId($id);
}
