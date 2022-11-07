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
 * VbhexCheckout Dispute Discuss interface.
 * @api
 */
interface DisputeDiscussInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ENTITY_ID             =   'entity_id';

    const DISPUTE_ID            =   'dispute_id';

    const AUTHOR_ID            =   'author_id';

    const AUTHOR_TYPE            =   'author_type';

    const DETAIL                  =   'detail';

    const CREATED_AT            =   'created_at';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get Dispute Id
     *
     * @return int|null
     */
    public function getDisputeId();


    /**
     * Get Author Id
     *
     * @return int|null
     */
    public function getAuthorId();


    /**
     * Get Author Type
     *
     * @return int|null
     */
    public function getAuthorType();


    /**
     * Get Detail
     *
     * @return string|null
     */
    public function getDetail();

    /**
     * Get Created At
     *
     * @return string|null
     */
    public function getCreatedAt();


    /**
     * Set ID
     *
     * @param int $id
     * @return \Vbhex\Checkout\Api\Data\ChainInterface
     */
    public function setId($id);
}
