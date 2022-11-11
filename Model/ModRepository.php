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

use Vbhex\Checkout\Api\ModRepositoryInterface;
use Vbhex\Checkout\Model\ResourceModel\Mod\CollectionFactory;

/**
 * VbhexCheckout Mod Model.
 *
 */
class ModRepository implements ModRepositoryInterface
{
    /**
     * @var ModFactory
     */
    protected $_modFactory;


    /**
     * Initialize resource model.
     */
    public function __construct(
        CollectionFactory $modFactory
    )
    {
        $this->_modFactory = $modFactory;
    }
    /**
     * Retrieve mod details which match a specified criteria.
     *
     * @api
     * @param int $id
     * @return \Vbhex\Checkout\Api\Data\ModInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getModMetaData($id)
    {
        $modCollection = $this->_modFactory->create()
        ->addFieldToSelect('*')
        ->addFieldToFilter('mod_id', $id);

        $data = array();

        foreach($modCollection->getItems() as $mod) {
            $data             =   [
                'image'             =>      $mod->getImage(),
                'external_url'      =>      $mod->getExternalUrl(),
                'name'              =>      $mod->getName(),
                'description'       =>      $mod->getDescription(),
                'attributes'        =>      unserialize($mod->getAttributes()),
                'background_color'  =>      $mod->getBackgroundColor(),
                'animation_url'     =>      $mod->getAnimationUrl(),
                'youtube_url'       =>      $mod->getYoutubeUrl()
            ];
            break;
        }

        echo json_encode($data,true);
        exit;

    }
}
