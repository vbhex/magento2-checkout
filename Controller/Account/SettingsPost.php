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

namespace Vbhex\Checkout\Controller\Account;

use Magento\Framework\App\Action\Action;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Customer\Model\Url as CustomerUrl;
use Vbhex\Checkout\Model\SellerFactory;

/**
 * Vbhex Checkout Account SettingsPost Controller.
 */
class SettingsPost extends Action
{
    /**
     * @var \Magento\Customer\Model\Session
     */
    protected $_customerSession;

    /**
     * @var \Magento\Framework\Data\Form\FormKey\Validator
     */
    protected $_formKeyValidator;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_date;

    /**
     * @var \Vbhex\Checkout\Helper\Data
     */
    protected $helper;

    /**
     * @var DataPersistorInterface
     */
    protected $dataPersistor;

    /**
     * @var CustomerUrl
     */
    protected $customerUrl;

    /**
     * @var SellerFactory
     */
    protected $sellerModel;

    /**
     * @var \Magento\Customer\Model\CustomerFactory
     */
    protected $customerModel;

    /**
     * @param Context                                          $context
     * @param Session                                          $customerSession
     * @param FormKeyValidator                                 $formKeyValidator
     * @param \Magento\Framework\Stdlib\DateTime\DateTime      $date
     * @param \Vbhex\Checkout\Helper\Data                      $helper
     * @param DataPersistorInterface                           $dataPersistor
     * @param CustomerUrl                                      $customerUrl
     * @param SellerFactory                                    $sellerModel
     * @param \Magento\Customer\Model\CustomerFactory          $customerModel
     */
    public function __construct(
        Context $context,
        Session $customerSession,
        FormKeyValidator $formKeyValidator,
        \Magento\Framework\Stdlib\DateTime\DateTime $date,
        \Vbhex\Checkout\Helper\Data $helper,
        DataPersistorInterface $dataPersistor,
        CustomerUrl $customerUrl,
        \Vbhex\Checkout\Model\SellerFactory $sellerModel,
        \Magento\Customer\Model\CustomerFactory $customerModel
    ) {
        $this->_customerSession = $customerSession;
        $this->_formKeyValidator = $formKeyValidator;
        $this->_date = $date;
        $this->helper = $helper;
        $this->dataPersistor = $dataPersistor;
        $this->customerUrl = $customerUrl;
        $this->sellerModel = $sellerModel;
        $this->customerModel = $customerModel;
        parent::__construct(
            $context
        );
    }

    /**
     * Retrieve customer session object.
     *
     * @return \Magento\Customer\Model\Session
     */
    protected function _getSession()
    {
        return $this->_customerSession;
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
     * Update Seller Profile Informations.
     *
     * @return \Magento\Framework\Controller\Result\RedirectFactory
     */
    public function execute()
    {
        /** @var \Magento\Framework\Controller\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();

        if ($this->getRequest()->isPost()) {
            try {
                if (!$this->_formKeyValidator->validate($this->getRequest())) {
                    return $this->resultRedirectFactory->create()->setPath(
                        '*/*/settings',
                        ['_secure' => $this->getRequest()->isSecure()]
                    );
                }

                $fields = $this->getRequest()->getParams();
                $errors = $this->validateprofiledata($fields);
                $sellerId = $this->helper->getCustomerId();
                $storeId = $this->helper->getCurrentStoreId();
                if (empty($errors)) {
                    $autoId = 0;
                    $collection = $this->sellerModel->create()
                    ->getCollection()
                    ->addFieldToFilter('seller_id', $sellerId)
                    ->addFieldToFilter('store_id', $storeId);
                    foreach ($collection as $value) {
                        $autoId = $value->getId();
                    }
                    $fields = $this->getSellerSettingsFields($fields);
                    // If seller data doesn't exist for current store
                    if (!$autoId) {
                        $sellerDefaultData = [];
                        $collection = $this->sellerModel->create()
                        ->getCollection()
                        ->addFieldToFilter('seller_id', $sellerId)
                        ->addFieldToFilter('store_id', 0);
                        foreach ($collection as $value) {
                            $sellerDefaultData = $value->getData();
                        }
                        foreach ($sellerDefaultData as $key => $value) {
                            if (empty($fields[$key]) && $key != 'entity_id') {
                                $fields[$key] = $value;
                            }
                        }
                    }
                    // Save seller data for current store
                    $value = $this->sellerModel->create()->load($autoId);
                    $value->addData($fields);
                    $value->setStoreId($storeId);
                    $value->setSellerId($sellerId);
                    $value->save();
                    try {
                        // clear cache
                        $this->helper->clearCache();
                        if (!empty($errors)) {
                            foreach ($errors as $message) {
                                $this->messageManager->addError($message);
                            }
                            $this->dataPersistor->set('vbhexcheckout_wallet_data', $fields);
                        } else {
                            $this->messageManager->addSuccess(
                                __('Dservice wallet information was successfully saved')
                            );
                            $this->dataPersistor->clear('vbhexcheckout_wallet_data');
                        }

                        return $this->resultRedirectFactory->create()->setPath(
                            '*/*/settings',
                            ['_secure' => $this->getRequest()->isSecure()]
                        );
                    } catch (\Exception $e) {
                        $this->helper->logDataInLogger(
                            "Controller_Account_SettingsPost execute : ".$e->getMessage()
                        );
                        $this->messageManager->addException($e, __('We can\'t save the customer.'));
                    }

                    return $this->resultRedirectFactory->create()->setPath(
                        '*/*/settings',
                        ['_secure' => $this->getRequest()->isSecure()]
                    );
                } else {
                    foreach ($errors as $message) {
                        $this->messageManager->addError($message);
                    }
                    $this->dataPersistor->set('vbhexcheckout_wallet_data', $fields);

                    return $this->resultRedirectFactory->create()->setPath(
                        '*/*/settings',
                        ['_secure' => $this->getRequest()->isSecure()]
                    );
                }
            } catch (\Exception $e) {
                $this->helper->logDataInLogger(
                    "Controller_Account_Settings execute : ".$e->getMessage()
                );
                $this->messageManager->addError($e->getMessage());
                $this->dataPersistor->set('vbhexcheckout_wallet_data', $fields);

                return $this->resultRedirectFactory->create()->setPath(
                    '*/*/settings',
                    ['_secure' => $this->getRequest()->isSecure()]
                );
            }
        } else {
            return $this->resultRedirectFactory->create()->setPath(
                '*/*/settings',
                ['_secure' => $this->getRequest()->isSecure()]
            );
        }
    }

    protected function validateprofiledata(&$fields)
    {
        $errors = [];
        $data = [];
        foreach ($fields as $code => $value) {
            switch ($code):
                case 'bsc_wallet_address':
                    if (trim($value) != '' &&
                        strlen($value) != 42 &&
                        substr($value, 0, 2) != '0x'
                    ) {
                        $errors[] = __('Invalid BSC Wallet Address');
                    } else {
                        $value = preg_replace("/<script.*?\/script>/s", "", $value) ? : $value;
                        $fields[$code] = $value;
                    }
                    break;
                case 'eth_wallet_address':
                    if (trim($value) != '' &&
                        strlen($value) != 42 &&
                        substr($value, 0, 2) != '0x'
                    ) {
                        $errors[] = __('Invalid Ethereum Wallet Address');
                    } else {
                        $value = preg_replace("/<script.*?\/script>/s", "", $value) ? : $value;
                        $fields[$code] = $value;
                    }
            endswitch;
        }

        return $errors;
    }

    protected function getSellerSettingsFields($fields = [])
    {
        return $fields;
    }
}
