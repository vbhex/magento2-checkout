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

namespace Vbhex\Checkout\Controller\Transaction;

use Magento\Framework\App\Action\Action;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Data\Form\FormKey\Validator as FormKeyValidator;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Customer\Model\Url as CustomerUrl;
use Vbhex\Checkout\Model\DisputeFactory;

/**
 * Vbhex Checkout Transaction Seller Post Controller.
 */
class SellerPost extends Action
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
     * @var DisputeFactory
     */
    protected $disputeModel;

    /**
     * @var DisputeDiscussFactory
     */
    protected $disputeDiscussModel;

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
     * @param DisputeFactory                                   $disputeModel
     * @param DisputeDiscussFactory                            $disputeDiscussModel
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
        \Vbhex\Checkout\Model\DisputeFactory $disputeModel,
        \Vbhex\Checkout\Model\DisputeDiscussFactory $disputeDiscussModel,
        \Magento\Customer\Model\CustomerFactory $customerModel
    ) {
        $this->_customerSession = $customerSession;
        $this->_formKeyValidator = $formKeyValidator;
        $this->_date = $date;
        $this->helper = $helper;
        $this->dataPersistor = $dataPersistor;
        $this->customerUrl = $customerUrl;
        $this->disputeModel = $disputeModel;
        $this->disputeDiscussModel = $disputeDiscussModel;
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
     * Update Dispute Seller Reason Informations.
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
                        '*/account/sellertrxs',
                        ['_secure' => $this->getRequest()->isSecure()]
                    );
                }

                $fields = $this->getRequest()->getParams();
                $errors = $this->validatedisputedata($fields);
                $sellerId = $this->helper->getCustomerId();
                $storeId = $this->helper->getCurrentStoreId();
                $appOrderId = $fields['app_order_id'] ?? 0;
                if (empty($errors)) {
                    $autoId = 0;
                    $collection = $this->disputeModel->create()
                    ->getCollection()
                    ->addFieldToFilter('seller_id', $sellerId)
                    ->addFieldToFilter('app_order_id', $appOrderId);
                    foreach ($collection as $value) {
                        $autoId = $value->getId();
                    }
                    $fields = $this->getDisputeFields($fields);
                    // If dispute data doesn't exist for current store
                    if (!$autoId) {
                        $this->messageManager->addError(
                            __('Dispute record not found!')
                        );
                        $this->dataPersistor->clear('vbhexcheckout_dispute_data');
                        return $this->resultRedirectFactory->create()->setPath(
                            '*/*/Sell/order/'.$appOrderId,
                            ['_secure' => $this->getRequest()->isSecure()]
                        );
                    }
                    // Save dispute discuss data for current dispute
                    $db_fields = array();
                    $db_fields['dispute_id']  = $autoId;
                    $db_fields['author_id'] =   $sellerId;
                    $db_fields['author_type'] = 2;
                    $db_fields['detail'] = $fields['seller_reason'];
                    $value = $this->disputeDiscussModel->create();
                    $value->addData($db_fields);
                    $value->save();
                    try {
                        // clear cache
                        $this->helper->clearCache();
                        if (!empty($errors)) {
                            foreach ($errors as $message) {
                                $this->messageManager->addError($message);
                            }
                            $this->dataPersistor->set('vbhexcheckout_dispute_data', $fields);
                        } else {
                            $this->messageManager->addSuccess(
                                __('Dispute seller reason successfully saved')
                            );
                            $this->dataPersistor->clear('vbhexcheckout_dispute_data');
                        }

                        return $this->resultRedirectFactory->create()->setPath(
                            '*/*/Sell/order/'.$appOrderId,
                            ['_secure' => $this->getRequest()->isSecure()]
                        );
                    } catch (\Exception $e) {
                        $this->helper->logDataInLogger(
                            "Controller_Transaction_SellerPost execute : ".$e->getMessage()
                        );
                        $this->messageManager->addException($e, __('We can\'t save the seller reason.'));
                    }

                    return $this->resultRedirectFactory->create()->setPath(
                        '*/*/Sell/order/'.$appOrderId,
                        ['_secure' => $this->getRequest()->isSecure()]
                    );
                } else {
                    foreach ($errors as $message) {
                        $this->messageManager->addError($message);
                    }
                    $this->dataPersistor->set('vbhexcheckout_dispute_data', $fields);

                    return $this->resultRedirectFactory->create()->setPath(
                        '*/*/Sell/order/'.$appOrderId,
                        ['_secure' => $this->getRequest()->isSecure()]
                    );
                }
            } catch (\Exception $e) {
                print($e->getMessage());exit;
                $this->helper->logDataInLogger(
                    "Controller_Transaction_SellerPost execute : ".$e->getMessage()
                );
                $this->messageManager->addError($e->getMessage());
                $this->dataPersistor->set('vbhexcheckout_dispute_data', $fields);

                return $this->resultRedirectFactory->create()->setPath(
                    '*/account/sellertrxs',
                    ['_secure' => $this->getRequest()->isSecure()]
                );
            }
        } else {
            $fields = $this->getRequest()->getParams();
            $appOrderId = $fields['app_order_id'] ?? 0;
            return $this->resultRedirectFactory->create()->setPath(
                '*/*/Sell/order/'.$appOrderId,
                ['_secure' => $this->getRequest()->isSecure()]
            );
        }
    }

    protected function validatedisputedata(&$fields)
    {
        $errors = [];
        $data = [];
        foreach ($fields as $code => $value) {
            switch ($code):
                case 'seller_reason':
                    $value = preg_replace("/<script.*?\/script>/s", "", $value) ? : $value;
                        $value = $this->helper->validateXssString($value);
                        $fields[$code] = $value;
                    break;
                case 'app_order_id':
                    if (trim($value) != '' &&
                        !is_numeric($value)
                    ) {
                        $errors[] = __('Invalid App Order Id');
                    } else {
                        $value = preg_replace("/<script.*?\/script>/s", "", $value) ? : $value;
                        $fields[$code] = $value;
                    }
            endswitch;
        }

        return $errors;
    }

    protected function getDisputeFields($fields = [])
    {
        return $fields;
    }
}
