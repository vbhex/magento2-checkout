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

namespace Vbhex\Checkout\Block\Account;

use Magento\Framework\App\Request\DataPersistorInterface;

/**
 * Vbhex Checkout Account Settings Block
 */
class Settings extends \Magento\Framework\View\Element\Template
{

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var \Vbhex\Checkout\Helper\Data
     */
    protected $helper;

    /**
     * @param \Magento\Directory\Model\ResourceModel\Country\CollectionFactory $countryCollectionFactory
     * @param \Magento\Framework\View\Element\Template\Context $context
     * @param DataPersistorInterface $dataPersistor
     * @param \Vbhex\Checkout\Helper\Data $helper
     * @param array $data
     */
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        DataPersistorInterface $dataPersistor,
        \Vbhex\Checkout\Helper\Data $helper,
        array $data = []
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->helper = $helper;
        parent::__construct($context, $data);
    }


    public function getPersistentData()
    {
        $partner = $this->helper->getVbhexCheckoutSettings();
        // $persistentData = (array)$this->dataPersistor->get('seller_vc_data');
        // foreach ($partner as $key => $value) {
        //     if (empty($persistentData[$key])) {
        //         $persistentData[$key] = $value;
        //     }
        // }
        // $this->dataPersistor->clear('seller_vc_data');
        // return $persistentData;
        return $partner;
    }
}
