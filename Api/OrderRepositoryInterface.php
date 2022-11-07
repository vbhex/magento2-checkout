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
 * Order CRUD interface.
 */
interface OrderRepositoryInterface
{
    /**
     * Create Order.
     *
     * @api
     * @param \Vbhex\Checkout\Api\Data\OrderInterface $order
     * @param string $passwordHash
     * @return \Vbhex\Checkout\Api\Data\OrderInterface
     * @throws \Magento\Framework\Exception\InputException If bad input is provided
     * @throws \Magento\Framework\Exception\State\InputMismatchException
     * If the provided order_id is already used
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Vbhex\Checkout\Api\Data\OrderInterface $order, $passwordHash = null);

    /**
     * Retrieve Order.
     *
     * @api
     * @param int $order_id
     * @param int|null $websiteId
     * @return \Vbhex\Checkout\Api\Data\OrderInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * If order with the specified order_id does not exist.
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($order_id, $websiteId = null);

    /**
     * Retrieve Order.
     *
     * @api
     * @param int $id
     * @return \Vbhex\Checkout\Api\Data\OrderInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * If order with the specified ID does not exist.
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($id);

    /**
     * Retrieve orders which match a specified criteria.
     *
     * @api
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Sales\Api\Data\OrderSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete Order.
     *
     * @api
     * @param \Vbhex\Checkout\Api\Data\OrderInterface $order
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Vbhex\Checkout\Api\Data\OrderInterface $order);

    /**
     * Delete Order by ID.
     *
     * @api
     * @param int $id
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($id);
}
