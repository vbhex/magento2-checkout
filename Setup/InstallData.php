<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Vbhex\Checkout\Setup;

use Magento\Framework\Module\Setup\Migration;
use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{

    protected $_coinFactory;

    protected $_chainFactory;

    /**
     * Init
     *
     */
    public function __construct(
        \Vbhex\Checkout\Model\CoinFactory $coinFactory,
        \Vbhex\Checkout\Model\ChainFactory $chainFactory
    )
    {
        $this->_coinFactory     =   $coinFactory;
        $this->_chainFactory    =   $chainFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        // coins table
        $coins = array();
        $coins[0]    =   [
            'chain_id'          =>  1,
            'name'              =>  'Vote Coin In Goerli',
            'symbol'            =>  'gVOTE',
            'decm'              =>  6,
            'contract_address'  =>  '0xebBA921554901aBc495Bf4C9f5E8F1C1c98078d9',
            'price'             =>  1.00,
            'logo'              =>  'https://vbhex.com/static/version1660269944/frontend/Magento/luma/en_US/Vbhex_Checkout/images/dispute/ic1.png',
            'is_valid'          =>  1,
            'is_currency'       =>  0,
            'sync_price'        =>  0
        ];
        $coins[1]    =   [
            'chain_id'          =>  1,
            'name'              =>  'Ether In Goerli',
            'symbol'            =>  'gETH',
            'decm'              =>  18,
            'contract_address'  =>  '0x0000000000000000000000000000000000000000',
            'price'             =>  221.00,
            'logo'              =>  'https://vbhex.com/static/version1660269944/frontend/Magento/luma/en_US/Vbhex_Checkout/images/dispute/ic4.png',
            'is_valid'          =>  1,
            'is_currency'       =>  0,
            'sync_price'        =>  0
        ];
        $coins[2]    =   [
            'chain_id'          =>  1,
            'name'              =>  'USD In Goerli',
            'symbol'            =>  'gUSDT',
            'decm'              =>  6,
            'contract_address'  =>  '0x01911aFA1A3d64D52451a22cB265FC34F596F425',
            'price'             =>  1.00,
            'logo'              =>  'https://vbhex.com/static/version1660269944/frontend/Magento/luma/en_US/Vbhex_Checkout/images/dispute/ic3.png',
            'is_valid'          =>  1,
            'is_currency'       =>  1,
            'sync_price'        =>  0
        ];
        $coins[3]    =   [
            'chain_id'          =>  4,
            'name'              =>  'Ether In Mainnet',
            'symbol'            =>  'ETH',
            'decm'              =>  18,
            'contract_address'  =>  '0x0000000000000000000000000000000000000000',
            'price'             =>  1.00,
            'logo'              =>  'https://vbhex.com/static/version1660269944/frontend/Magento/luma/en_US/Vbhex_Checkout/images/dispute/ic4.png',
            'is_valid'          =>  0,
            'is_currency'       =>  0,
            'sync_price'        =>  1
        ];

        foreach($coins as $coin) {
            $model_coin = $this->_coinFactory->create();
            $model_coin->addData($coin)->save();
            $model_coin->afterSave();
        }

        // chain table
        $chains = array();
        $chains[0]      =   [
            'name'          =>  'Goerli Network',
            'from_block'    =>  7379290,
            'buy_vote_block'    =>  7379290
        ];
        $chains[1]      =   [
            'name'          =>  'Ropsten Network',
            'from_block'    =>  7379273,
            'buy_vote_block'    =>  7379273
        ];
        $chains[2]      =   [
            'name'          =>  'BSC testnet',
            'from_block'    =>  20844846
        ];
        $chains[3]      =   [
            'name'          =>  'Ethereum Mainnet',
            'from_block'    =>  7379273,
            'buy_vote_block'    =>  7379273
        ];
        foreach($chains as $chain) {
            $model_chain = $this->_chainFactory->create();
            $model_chain->addData($chain)->save();
            $model_chain->afterSave();
        }

    }
}
