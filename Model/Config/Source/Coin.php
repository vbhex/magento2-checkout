<?php
namespace Vbhex\Checkout\Model\Config\Source;

class Coin implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Retrieve Custom Option array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 1, 'label' => __('BNB')],
            ['value' => 2, 'label' => __('USDT')]
        ];
    }
}
