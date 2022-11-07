<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Vbhex\Checkout\Model\Ui;

use \Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Checkout\Model\Cart;
use Magento\Checkout\Model\ConfigProviderInterface;

/**
 * Class ConfigProvider
 */
class ConfigProvider implements ConfigProviderInterface
{

    const CODE = 'sample_gateway1';

    protected $_scopeConfig;

    protected $_cart;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Checkout\Model\Cart $cart
        )
    {
        $this->_scopeConfig = $scopeConfig;
        $this->_cart = $cart;
    }

    public function getConfig()
    {

        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $tableName = $resource->getTableName('vc_coins');

        if($connection->tableColumnExists($tableName, 'is_valid') === false){
            $connection->addColumn('vc_coins', 'is_valid', array(
                'type'      => \Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
                'nullable'  => false,
                'length'    => 1,
                'default'   => 1,
                'after'     => 'price', // column name to insert new column after
                'comment'   => '1 on valid wallet else 0'
            ));
        }

        //get valid coins for vc
        $sql = "SELECT `entity_id`,`symbol` FROM vc_coins WHERE `is_valid` = '1' AND `is_currency` = '1'";
        $result = $connection->fetchAll($sql);

        // get sellers count in one cart
        $quoteId = $this->_cart->getQuote()->getId();
        $sql1 = "SELECT  `seller_id` from `quote_item`  left join `marketplace_product` on
                `quote_item`.`product_id` = `marketplace_product`.`mageproduct_id`
                 where `quote_item`.`quote_id` = ".intval($quoteId). " Group by `seller_id` ";
        $result1 = $connection->fetchAll($sql1);
        return [
            'payment' => [
                self::CODE => [
                    'validCoins' => $result,
                    'payment_description' => $this->getStoreConfig('payment/vbhex_checkout/description'),
                    'isWallets' => !empty($result) ? true : false,
                    'singleSeller' => (count($result1) == 1) ? true : false
                ]
            ]
        ];
    }
    public function getStoreConfig($_env)
    {
        $_val = $this->_scopeConfig->getValue(
            $_env, \Magento\Store\Model\ScopeInterface::SCOPE_STORE);
        return $_val;

    }

}
