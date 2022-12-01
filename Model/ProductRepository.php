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

use Vbhex\Checkout\Api\ProductRepositoryInterface;
use Magento\Framework\AppInterface;

/**
 * VbhexCheckout Mod Model.
 *
 */
class ProductRepository implements ProductRepositoryInterface
{

    protected $dir;

    /**
     * Initialize resource model.
     */
    public function __construct(\Magento\Framework\Filesystem\DirectoryList $dir)
    {
        $this->dir = $dir;
    }


    /**
     * Delete product by product_id
     *
     * @api
     * @param int $id
     * @return string
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delProduct($id)
    {
        try {
            require_once $this->dir->getRoot(). '/app/bootstrap.php';
        } catch (\Exception $e) {
            echo 'Autoload error: ' . $e->getMessage();
            exit(1);
        }
        try {
            $bootstrap = \Magento\Framework\App\Bootstrap::create(BP, $_SERVER);
            $objectManager = $bootstrap->getObjectManager();
            $appState = $objectManager->get('\Magento\Framework\App\State');
            $appState->setAreaCode('frontend');

            $productRepository = $objectManager->get('\Magento\Catalog\Model\ProductRepository');
            $registry = $objectManager->get('\Magento\Framework\Registry');

            $registry->register('isSecureArea', true);

            //using product id to remove product
            $product_id = $id; //here your product id
            $product = $productRepository->getById($product_id);
            $productRepository->delete($product);

            $data = [
                'success'   =>  true,
                'msg'       =>  "product with id " . $id . " deleted successfully"
            ];
            header('Content-Type: application/json');
            echo json_encode($data, JSON_PRETTY_PRINT);
            exit;
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
    }
}
