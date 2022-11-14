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
                // 'background_color'  =>      $mod->getBackgroundColor(),
                // 'animation_url'     =>      $mod->getAnimationUrl(),
                // 'youtube_url'       =>      $mod->getYoutubeUrl()
            ];
            break;
        }
        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);
        exit;

    }
    /**
     * Retrieve mod contract meta data.
     *
     * @api
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getContractMeta()
    {
        $data = array();
        $data             =   [
            'name'             =>      "Vbhex Moderators",
            'description'      =>      "Vbhex Moderators Contract",
            'image'              =>    "https://d1wlhu0novpxl3.cloudfront.net\mods\Zombatar_41.jpg",
            'external_link'       =>    "https://vbhex.com",
            'seller_fee_basis_points'        =>      100,
            'fee_recipient'     =>  "0x59B2309A468E0f6c94303D7752A019E57562bD36"
        ];

        header('Content-Type: application/json');
        echo json_encode($data,JSON_PRETTY_PRINT);
        exit;
    }
}
