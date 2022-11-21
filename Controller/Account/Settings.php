<?php
/**
 * Vbhex Software.
 *
 * @category  Vbhex
 * @package   Vbhex_Checkout
 * @author    Vbhex
 * @copyright Copyright (c) Vbhex Software Private Limited (https://Vbhex.com)
 * @license   https://store.Vbhex.com/license.html
 */

namespace Vbhex\Checkout\Controller\Account;

use Magento\Framework\App\Action\Context;
use Magento\Framework\View\Result\PageFactory;
use Magento\Framework\App\RequestInterface;
use Vbhex\Checkout\Helper\Data as VbhexCheckoutHelper;
use Magento\Customer\Model\Url as CustomerUrl;

/**
 * VbhexCheckout Account Settings Controller.
 */
class Settings extends \Magento\Customer\Controller\AbstractAccount
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var PageFactory
     */
    protected $_resultPageFactory;

    /**
     * @var VbhexCheckoutHelper
     */
    protected $vcHelper;

    /**
     * @var CustomerUrl
     */
    protected $customerUrl;

    /**
     * @param Context     $context
     * @param PageFactory $resultPageFactory
     * @param VbhexCheckoutHelper $vcHelper
     * @param CustomerUrl $customerUrl
     */
    public function __construct(
        Context $context,
        PageFactory $resultPageFactory,
        \Magento\Customer\Model\Session $customerSession,
        VbhexCheckoutHelper $vcHelper,
        CustomerUrl $customerUrl
    ) {
        $this->_customerSession = $customerSession;
        $this->_resultPageFactory = $resultPageFactory;
        $this->vcHelper = $vcHelper;
        $this->customerUrl = $customerUrl;
        parent::__construct($context);
    }

    /**
     * Check customer authentication.
     *
     * @param RequestInterface $request
     *
     * @return \Magento\Framework\App\ResponseInterface
     */
    public function dispatch(RequestInterface $request)
    {
        $loginUrl = $this->customerUrl->getLoginUrl();

        if (!$this->_customerSession->authenticate($loginUrl)) {
            $this->_actionFlag->set('', self::FLAG_NO_DISPATCH, true);
        }

        return parent::dispatch($request);
    }

    /**
     * Setting Seller VbhexCheckout page.
     *
     * @return \Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $helper = $this->vcHelper;
        // if (!$helper->getSellerProfileDisplayFlag()) {
        //     $this->getRequest()->initForward();
        //     $this->getRequest()->setActionName('noroute');
        //     $this->getRequest()->setDispatched(false);
        //     return false;
        // }
        $isPartner = $helper->isSeller();
        if ($isPartner == 1) {
            /** @var \Magento\Framework\View\Result\Page $resultPage */
            $resultPage = $this->_resultPageFactory->create();
            $resultPage->getConfig()->getTitle()->set(__('VBHex Seller Account Settings Page'));

            return $resultPage;
        } else {
            return $this->resultRedirectFactory->create()->setPath(
                'marketplace/account/becomeseller',
                ['_secure' => $this->getRequest()->isSecure()]
            );
        }
    }
}
