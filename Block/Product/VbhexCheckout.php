<?php

namespace Vbhex\Checkout\Block\Product;

/*
 * Vbhex Checkout Product Detail Page Block
 */

class VbhexCheckout extends \Magento\Framework\View\Element\Template
{

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Prepare HTML content.
     *
     * @return string
     */
    public function getCmsFilterContent($value = '')
    {
        return $this->filterProvider->getPageFilter()->filter($value);
    }
}
