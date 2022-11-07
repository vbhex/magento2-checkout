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
namespace Vbhex\Checkout\Api;

/**
 * Coin CRUD interface.
 */
interface CoinRepositoryInterface
{
    /**
     * Retrieve coins which match a specified criteria.
     *
     * @api
     * @return \Vbhex\Checkout\Api\Data\CoinInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getActiveCoins();
}
