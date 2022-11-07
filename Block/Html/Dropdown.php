<?php
namespace Vbhex\Checkout\Block\Html;

use Webkul\Marketplace\Helper\Data as MpHelper;

class Dropdown extends \Magento\Framework\View\Element\Html\Link
{
    /**
     * @var MpHelper
     */
    protected $mpHelper;
    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        MpHelper $mpHelper,
        array $data = []
    ){
        parent::__construct($context, $data);
        $this->mpHelper = $mpHelper;
    }
   protected function _toHtml()
    {
		$objectManager = \Magento\Framework\App\ObjectManager::getInstance();
		$storeManager = $objectManager->get('\Magento\Store\Model\StoreManagerInterface');
		$context = $objectManager->get('Magento\Framework\App\Http\Context');
		$isLoggedIn = $context->getValue(\Magento\Customer\Model\Context::CONTEXT_AUTH);
		$base_url=$storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_WEB);
        $isPartner= $this->mpHelper->isSeller();
     if (false != $this->getTemplate()) {
     	return parent::_toHtml();
     }
	 if($isLoggedIn) {
        if($isPartner) {
            return '<li class="vbhexcheckout-account-main"><a ' . $this->getLinkAttributes() . ' >' . $this->escapeHtml($this->getLabel()) . '</a>
            <span class="vbhexcheckout-account-name">
                   <button type="button" class="vbhexcheckout-button">
                       <span>Change</span>
                   </button>
               </span>
            <div class="vbhexcheckout-account-menu" data-target="dropdown" aria-hidden="true">
                       <ul class="vbhexcheckout-account-header">
                           <li><a href="'.$base_url.'vbhexcheckout/account/settings">Dservice Seller Settings</a></li>
                           <li><a href="'.$base_url.'vbhexcheckout/account/sellertrxs">Dservice Seller Orders</a></li>
                           <li><a href="'.$base_url.'vbhexcheckout/account/buyertrxs">Dservice Buyer Orders</a></li></ul>
                       </div>
            </li>';
        } else {
            return '<li class="vbhexcheckout-account-main"><a ' . $this->getLinkAttributes() . ' >' . $this->escapeHtml($this->getLabel()) . '</a>
            <span class="vbhexcheckout-account-name">
                   <button type="button" class="vbhexcheckout-button">
                       <span>Change</span>
                   </button>
               </span>
            <div class="vbhexcheckout-account-menu" data-target="dropdown" aria-hidden="true">
                       <ul class="vbhexcheckout-account-header">
                           <li><a href="'.$base_url.'vbhexcheckout/account/buyertrxs">Dservice Buyer Orders</a></li></ul>
                       </div>
            </li>';
        }

	 }else
	 {
		 return '<li class="vbhexcheckout-account-main"><a ' . $this->getLinkAttributes() . ' >' . $this->escapeHtml($this->getLabel()) . '</a></li>';
	 }

    }
}
