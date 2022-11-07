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

namespace Vbhex\Checkout\Model;

use Magento\Framework\Model\AbstractModel;
use Vbhex\Checkout\Api\Data\DisputeDiscussInterface;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * Dservice Dispute Model.
 *
 * @method \Vbhex\CheckoutModel\ResourceModel\Dispute _getResource()
 * @method \Vbhex\Checkout\Model\ResourceModel\Dispute getResource()
 */
class DisputeDiscuss extends AbstractModel implements DisputeDiscussInterface, IdentityInterface
{
    /**
     * No route page id.
     */
    const NOROUTE_ENTITY_ID = 'no-route';

    /**#@+
     *
     */
    /**#@-*/

    /**
     * Dservice Dispute cache tag.
     */
    const CACHE_TAG = 'vbhexcheckout_dispute_discuss';

    /**
     * @var string
     */
    protected $_cacheTag = 'vbhexcheckout_dispute_discuss';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'vbhexcheckout_dispute_discuss';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init(
            \Vbhex\Checkout\Model\ResourceModel\DisputeDiscuss::class
        );
    }

    /**
     * Load object data.
     *
     * @param int|null $id
     * @param string   $field
     *
     * @return $this
     */
    public function load($id, $field = null)
    {
        if ($id === null) {
            return $this->noRouteDispute();
        }

        return parent::load($id, $field);
    }

    /**
     * Load No-Route Dispute.
     *
     * @return \Vbhex\Checkout\Model\Dispute
     */
    public function noRouteDispute()
    {
        return $this->load(self::NOROUTE_ENTITY_ID, $this->getIdFieldName());
    }

    /**
     * Get identities.
     *
     * @return array
     */
    public function getIdentities()
    {
        return [self::CACHE_TAG.'_'.$this->getId()];
    }

    /**
     * Get ID.
     *
     * @return int
     */
    public function getId()
    {
        return parent::getData(self::ENTITY_ID);
    }

    /**
     * Get Dispute ID.
     *
     * @return int
     */
    public function getDisputeId()
    {
        return parent::getData(self::DISPUTE_ID);
    }

    /**
     * Get Author Id
     *
     * @return int|null
     */
    public function getAuthorId()
    {
        return parent::getData(self::AUTHOR_ID);
    }


    /**
     * Get Author Type
     *
     * @return int|null
     */
    public function getAuthorType()
    {
        return parent::getData(self::AUTHOR_TYPE);
    }


    /**
     * Get Detail
     *
     * @return string|null
     */
    public function getDetail()
    {
        return parent::getData(self::DETAIL);
    }

    /**
     * Get Created At
     *
     * @return string|null
     */
    public function getCreatedAt()
    {
        return parent::getData(self::CREATED_AT);
    }

    /**
     * Set ID.
     *
     * @param int $id
     *
     * @return \Vbhex\Checkout\Api\Data\DisputeInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }
}
