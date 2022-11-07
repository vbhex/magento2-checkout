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
 * Dservice Chain interface.
 * @api
 */
interface ChainInterface
{
    /**#@+
     * Constants for keys of data array. Identical to the name of the getter in snake case
     */
    const ENTITY_ID             =   'id';

    const NAME                  =   'name';

    const FROM_BLOCK            =   'from_block';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get Name
     *
     * @return string|null
     */
    public function getName();


    /**
     * Get From Block
     *
     * @return int|null
     */
    public function getFromBlock();



    /**
     * Set ID
     *
     * @param int $id
     * @return \Vbhex\Checkout\Api\Data\ChainInterface
     */
    public function setId($id);
}
