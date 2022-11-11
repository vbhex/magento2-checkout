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
use Vbhex\Checkout\Api\Data\ModInterface;
use Magento\Framework\DataObject\IdentityInterface;

/**
 * VbhexCheckout Mod Model.
 *
 * @method \Vbhex\CheckoutModel\ResourceModel\Mod _getResource()
 * @method \Vbhex\Checkout\Model\ResourceModel\Mod getResource()
 */
class Mod extends AbstractModel implements ModInterface, IdentityInterface
{
    /**
     * No route page id.
     */
    const NOROUTE_ENTITY_ID = 'no-route';

    /**
     * VbhexCheckout Mod cache tag.
     */
    const CACHE_TAG = 'vc_mods';

    /**
     * @var string
     */
    protected $_cacheTag = 'vc_mods';

    /**
     * Prefix of model events names.
     *
     * @var string
     */
    protected $_eventPrefix = 'vc_mods';

    /**
     * Initialize resource model.
     */
    protected function _construct()
    {
        $this->_init(
            \Vbhex\Checkout\Model\ResourceModel\Mod::class
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
            return $this->noRouteMod();
        }

        return parent::load($id, $field);
    }

    /**
     * Load No-Route Mod.
     *
     * @return \Vbhex\Checkout\Model\Mod
     */
    public function noRouteMod()
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
     * Get Mod ID.
     *
     * @return int
     */
    public function getModId()
    {
        return parent::getData(self::MOD_ID);
    }

    /**
     * Get Image
     *
     * @return string|null
     */
    public function getImage()
    {
        return parent::getData(self::IMAGE);
    }

    /**
     * Get Name
     *
     * @return string|null
     */
    public function getName()
    {
        return parent::getData(self::NAME);
    }

    /**
     * Get External Url
     *
     * @return string|null
     */
    public function getExternalUrl()
    {
        return parent::getData(self::EXTERNAL_URL);
    }

    /**
     * Get Description
     *
     * @return string|null
     */
    public function getDescription()
    {
        return parent::getData(self::DESCRIPTIOM);
    }

    /**
     * Get Attributes
     *
     * @return string|null
     */
    public function getAttributes()
    {
        return parent::getData(self::ATTRIBUTES);
    }

    /**
     * Get Background Color
     *
     * @return string|null
     */
    public function getBackgroundColor()
    {
        return parent::getData(self::BACKGROUND_COLOR);
    }

    /**
     * Get Animation Url
     *
     * @return string|null
     */
    public function getAnimationUrl()
    {
        return parent::getData(self::ANIMATION_URL);
    }

    /**
     * Get Youtube Url
     *
     * @return string|null
     */
    public function getYoutubeUrl()
    {
        return parent::getData(self::YOUTUBE_URL);
    }

    /**
     * Get Created At
     *
     * @return date|null
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
     * @return \Vbhex\Checkout\Api\Data\ModInterface
     */
    public function setId($id)
    {
        return $this->setData(self::ENTITY_ID, $id);
    }
}
