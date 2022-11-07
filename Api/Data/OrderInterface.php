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
 * Dservice Order interface.
 * @api
 */
interface OrderInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ENTITY_ID    = 'entity_id';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Set ID
     *
     * @param int $id
     * @return \Vbhex\Checkout\Api\Data\OrderInterface
     */
    public function setId($id);

    /**
     * Update Dservice Order as well as Original magento Order
     *
     * @param int $id
     * @param int $order_id
     * @param int $block
     * @param array $customer
     * @return string
     */
    public function updateOrder($id, $order_id, $block, $customer);
}
