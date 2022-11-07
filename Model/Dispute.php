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
use Vbhex\Checkout\Api\Data\DisputeInterface;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * VbhexCheckout Dispute Model.
 *
 * @method \Vbhex\CheckoutModel\ResourceModel\Dispute _getResource()
 * @method \Vbhex\Checkout\Model\ResourceModel\Dispute getResource()
 */
class Dispute extends AbstractModel implements DisputeInterface, IdentityInterface
{
    /**
     * No route page id.
     */
    const NOROUTE_ENTITY_ID = 'no-route';

    /**#@+
     * Dispute's Statuses
     */
    const STATUS_ENABLED    = 1;
    const STATUS_PENDING    = 0;
    const STATUS_PROCESSING = 2;
    const STATUS_DISABLED   = 3;
    /**#@-*/

    /**
     * VbhexCheckout Dispute cache tag.
     */
    const CACHE_TAG = 'vc_dispute';

    /**
     * @var string
     */
    protected $_cacheTag = 'vc_dispute';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'vc_dispute';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init(
            \Vbhex\Checkout\Model\ResourceModel\Dispute::class
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
