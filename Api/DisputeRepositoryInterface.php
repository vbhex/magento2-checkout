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
interface DisputeRepositoryInterface
{
    /**
     * Create Dispute.
     *
     * @api
     * @param \Vbhex\Checkout\Api\Data\DisputeInterface $dispute
     * @param string $passwordHash
     * @return \Vbhex\Checkout\Api\Data\DisputeInterface
     * @throws \Magento\Framework\Exception\InputException If bad input is provided
     * @throws \Magento\Framework\Exception\State\InputMismatchException
     * If the provided email is already used
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(\Vbhex\Checkout\Api\Data\DisputeInterface $dispute, $passwordHash = null);

    /**
     * Retrieve Dispute.
     *
     * @api
     * @param string $email
     * @param int|null $websiteId
     * @return \Vbhex\Checkout\Api\Data\DisputeInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * If dispute with the specified email does not exist.
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($email, $websiteId = null);

    /**
     * Retrieve Dispute.
     *
     * @api
     * @param int $disputeId
     * @return \Vbhex\Checkout\Api\Data\DisputeInterface
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * If dispute with the specified ID does not exist.
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getById($disputeId);

    /**
     * Retrieve disputes which match a specified criteria.
     *
     * @api
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Vbhex\Checkout\Api\Data\DisputeSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(\Magento\Framework\Api\SearchCriteriaInterface $searchCriteria);

    /**
     * Delete Dispute.
     *
     * @api
     * @param \Vbhex\Checkout\Api\Data\DisputeInterface $dispute
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(\Vbhex\Checkout\Api\Data\DisputeInterface $dispute);

    /**
     * Delete Dispute by ID.
     *
     * @api
     * @param int $disputeId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($disputeId);
}
