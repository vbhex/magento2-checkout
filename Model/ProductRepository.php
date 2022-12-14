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
    public function delProduct($id, $ali,$url_key)
    {
        try {
            require_once $this->dir->getRoot() . '/app/bootstrap.php';
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
            $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
            $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
            $connection = $resource->getConnection();
            //using product id to remove product
            $this->dodel($id, $ali,$url_key, $productRepository, $connection);
            // get related products

            $sql = "select
                    product_id
                    from catalog_product_super_link
                    where parent_id = " . $id . " ";
            $result = $connection->fetchAll($sql);
            foreach ($result as $v) {
                $this->dodel($v['product_id'], 0,"", $productRepository, $connection);
            }

            $data = [
                'sub_products'   =>  $result,
                'ali_product_id'    =>  $ali,
                'url_key'          =>   $url_key,
                'msg'       =>  "product with id " . $id . " deleted successfully"
            ];
            header('Content-Type: application/json');
            echo json_encode($data, JSON_PRETTY_PRINT);
            exit;
        } catch (\Exception $e) {
            print_r($e->getMessage());
        }
    }

    private function dodel($id, $ali,$url_key, $productRepository, $connection)
    {
        try {
            $product = $productRepository->getById($id);
        } catch (\Magento\Framework\Exception\NoSuchEntityException $e) {
            $product = false;
        }

        //delete all the attributes in table catalog_product_entity_int
        $sql1 = "delete from catalog_product_entity_int
        where entity_id = $id ";
        $connection->query($sql1);

        //delete all the attributes in table catalog_product_entity_varchar
        $sql2 = "delete from catalog_product_entity_varchar
       where entity_id = $id ";
        $connection->query($sql2);

        //delete all the url rewrite keys in table url_rewrite
//         $sql3a = "select request_path from url_rewrite where entity_id = $id ";
//         $result = $connection->fetchAll($sql3a);
//         $url = $result[0]['request_path'] ?? "";
//         if ($url !== "") {
//             $sub = substr($url, 0, -8);
//             $sql3b = "delete from url_rewrite
//    where request_path like '" . $sub . "%' ";
//             $connection->query($sql3b);
//         }

        if ($url_key !== "") {
            $sub = $url_key;
            $sql3b = "delete from url_rewrite
   where request_path like '%".$sub."%' ";
            $connection->query($sql3b);
        }


        if (intval($ali) !== 0) {
            //delete all the ali express in table mp_aliexpress_products
            $sql4 = "delete from mp_aliexpress_products where aliexpress_product_id like '" . $ali . "' ";
            $connection->query($sql4);
        }
        if ($product) {
            $productRepository->delete($product);
        }
        $sql0 = "delete from catalog_product_entity where sku like '".$ali."%'";
        $connection->query($sql0);
    }
}
