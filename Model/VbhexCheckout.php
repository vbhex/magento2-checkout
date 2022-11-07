<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Vbhex\Checkout\Model;

use Magento\Framework\DataObject;
use Magento\Quote\Api\Data\PaymentInterface;

/**
 * Pay In Store payment method model
 */
class VbhexCheckout extends \Magento\Payment\Model\Method\AbstractMethod
{
    const PAYMENT_METHOD_WALLET_CODE = 'vbhex_checkout';
    /**
     * Payment code
     *
     * @var string
     */
    protected $_code = self::PAYMENT_METHOD_WALLET_CODE;

    /**
     * Availability option
     *
     * @var bool
     */
    protected $_isOffline = false;

    /**
     * Assign data to info model instance
     *
     * @param \Magento\Framework\DataObject|mixed $data
     * @return $this
     * @throws \Magento\Framework\Exception\LocalizedException
    */
    public function assignData(\Magento\Framework\DataObject $data)
    {
        /*$additionalData = $data->getData(PaymentInterface::KEY_ADDITIONAL_DATA);
        if (!is_object($additionalData)) {
            $additionalData = new DataObject($additionalData ?: []);
        }

        $this->getInfoInstance()->setWalletCoin($additionalData->getWalletCoin());
        return $this;*/

        parent::assignData($data);
        $infoInstance = $this->getInfoInstance();


        if(is_array($data->getData('additional_data')))
        {
            $additionalData = $data->getData('additional_data');
            $pickPayLocation = isset($additionalData['selected_coin'])?$additionalData['selected_coin']:"";
            $infoInstance->setAdditionalInformation('selected_coin', $pickPayLocation);
            $data->setSelectedCoin($pickPayLocation);
            $infoInstance->setSelectedCoin($pickPayLocation);
        }
        return $this;
    }
}
