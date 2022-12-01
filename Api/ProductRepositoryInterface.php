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
 * Product CRUD interface.
 */
interface ProductRepositoryInterface
{
    /**
     * Delete product by product_id
     *
     * @api
     * @param int $id
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delProduct($id);
}
