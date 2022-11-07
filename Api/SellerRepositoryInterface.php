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
 * Seller CRUD interface.
 */
interface SellerRepositoryInterface
{
    /**
     * Create Seller.
     *
     * @api
     * @param \Vbhex\Checkout\Api\Data\SellerInterface $customer
     * @param string $passwordHash
     * @return \Vbhex\Checkout\Api\Data\SellerInterface
     * @throws \Magento\Framework\Exception\InputException If bad input is provided
     * @throws \Magento\Framework\Exception\State\InputMismatchException
     * If the provided email is already used
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Vbhex\Checkout\Api\Data\SellerInterface $customer, $passwordHash = null);

    /**
     * Retrieve Seller.
     *
     * @api
     * @param string $email
     * @param int|null $websiteId
     * @return \Vbhex\Checkout\Api\Data\SellerInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * If customer with the specified email does not exist.
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($email, $websiteId = null);

    /**
     * Retrieve Seller.
     *
     * @api
     * @param int $customerId
     * @return \Vbhex\Checkout\Api\Data\SellerInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * If customer with the specified ID does not exist.
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($customerId);

    /**
     * Retrieve customers which match a specified criteria.
     *
     * @api
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Magento\Customer\Api\Data\CustomerSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete Seller.
     *
     * @api
     * @param \Vbhex\Checkout\Api\Data\SellerInterface $customer
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Vbhex\Checkout\Api\Data\SellerInterface $customer);

    /**
     * Delete Seller by ID.
     *
     * @api
     * @param int $customerId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($customerId);
}
