<?php
/**
 * Copyright © Vbhex, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Vbhex\Checkout\Api\Data;

/**
 * coin search result interface.
 *
 * @api
 * @since 100.0.2
 */
interface CoinSearchResultInterface extends \Magento\Framework\Api\SearchResultsInterface
{
    /**
     * Gets collection items.
     *
     * @return \Vbhex\Checkout\Api\Data\CoinInterface Array of collection items.
     */
    public function getItems();

    /**
     * Set collection items.
     *
     * @param \Vbhex\Checkout\Api\Data\CoinInterface $items
     * @return $this
     */
    public function setItems(array $items);
}
