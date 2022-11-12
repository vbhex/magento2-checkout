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
 * Mod CRUD interface.
 */
interface ModRepositoryInterface
{
    /**
     * Retrieve mod details which match a specified criteria.
     *
     * @api
     * @param int $id
     * @return \Vbhex\Checkout\Api\Data\ModInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getModMetaData($id);

    /**
     * Retrieve mod contract meta data.
     *
     * @api
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getContractMeta();
}
