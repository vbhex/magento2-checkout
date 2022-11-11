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
 * VbhexCheckout Mod interface.
 * @api
 */
interface ModInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ENTITY_ID             =   'entity_id';

    const MOD_ID                =   'mod_id';

    const NAME                  =   'name';

    const IMAGE                 =   'image';

    const EXTERNAL_URL          =   'external_url';

    const DESCRIPTIOM           =   'description';

    const ATTRIBUTES            =   'attributes';

    const BACKGROUND_COLOR      =   'background_color';

    const ANIMATION_URL         =   'animation_url';

    const YOUTUBE_URL           =   'youtube_url';

    const CREATED_AT            =   'created_at';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get Mod ID
     *
     * @return int|null
     */
    public function getModId();

    /**
     * Get Name
     *
     * @return string|null
     */
    public function getName();

    /**
     * Get Image
     *
     * @return string|null
     */
    public function getImage();

    /**
     * Get External Url
     *
     * @return string|null
     */
    public function getExternalUrl();

    /**
     * Get Description
     *
     * @return string|null
     */
    public function getDescription();

    /**
     * Get Attributes
     *
     * @return string|null
     */
    public function getAttributes();

    /**
     * Get Background Color
     *
     * @return string|null
     */
    public function getBackgroundColor();

    /**
     * Get Animation Url
     *
     * @return string|null
     */
    public function getAnimationUrl();

    /**
     * Get Youtube Url
     *
     * @return string|null
     */
    public function getYoutubeUrl();

    /**
     * Get Created At
     *
     * @return date|null
     */
    public function getCreatedAt();

    /**
     * Set ID
     *
     * @param int $id
     * @return \Vbhex\Checkout\Api\Data\ModInterface
     */
    public function setId($id);
}
