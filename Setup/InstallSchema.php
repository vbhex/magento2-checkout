<?php
namespace Vbhex\Checkout\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{

	public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
	{
		$installer = $setup;
		$installer->startSetup();
		if (!$installer->tableExists('vc_coins')) {
			$table = $installer->getConnection()->newTable(
				$installer->getTable('vc_coins')
			)
				->addColumn(
					'entity_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					[
						'identity' => true,
						'nullable' => false,
						'primary'  => true,
						'unsigned' => true,
					],
					'ID'
				)
                ->addColumn(
					'chain_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					['nullable' => false, 'default' => 0],
					'Chain Id'
				)
				->addColumn(
					'name',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					55,
					['nullable => false'],
					'Name'
				)
                ->addColumn(
					'symbol',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					55,
					['nullable => false'],
					'Symbol'
				)
                ->addColumn(
					'decm',
					\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
					null,
					['nullable' => true, 'default' => 0],
					'Decm'
				)
                ->addColumn(
					'contract_address',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					55,
					['nullable => false'],
					'Contract Address'
				)
				->addColumn(
					'price',
					\Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
					[12,2],
					['nullable' => true, 'default' => 0.00],
					'Price'
				)
                ->addColumn(
					'logo',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					['nullable => false'],
					'Coin Logo URL'
				)
				->addColumn(
					'is_valid',
					\Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
					null,
					['nullable' => true, 'default' => true],
					'Is Valid'
				)
                ->addColumn(
					'is_currency',
					\Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
					null,
					['nullable' => true, 'default' => true],
					'Is Currency'
				)
                ->addColumn(
					'sync_price',
					\Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
					null,
					['nullable' => true, 'default' => true],
					'Is Sync Price'
				)
				->addColumn(
						'created_at',
						\Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
						null,
						['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
						'Created At'
				)
				->setComment('Vbhex Coins Table');
			$installer->getConnection()->createTable($table);

			$installer->getConnection()->addIndex(
				$installer->getTable('vc_coins'),
				$setup->getIdxName(
					$installer->getTable('vc_coins'),
					['name','symbol','contract_address'],
					\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
				),
				['name','symbol','contract_address'],
				\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
			);
		}

        if (!$installer->tableExists('vc_chain')) {
			$table = $installer->getConnection()->newTable(
				$installer->getTable('vc_chain')
			)
				->addColumn(
					'id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					[
						'identity' => true,
						'nullable' => false,
						'primary'  => true,
						'unsigned' => true,
					],
					'ID'
				)
				->addColumn(
					'name',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					55,
					['nullable => false'],
					'Name'
				)
                ->addColumn(
					'from_block',
					\Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
					18,
					['nullable' => false, 'default' => 0],
					'From Block'
				)
                ->addColumn(
					'buy_vote_block',
					\Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
					18,
					['nullable' => false, 'default' => 0],
					'Buy Vote From Block'
				)
				->setComment('VbhexCheckout Chain Table');
			$installer->getConnection()->createTable($table);

			$installer->getConnection()->addIndex(
				$installer->getTable('vc_chain'),
				$setup->getIdxName(
					$installer->getTable('vc_chain'),
					['name'],
					\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
				),
				['name'],
				\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
			);
		}

        if (!$installer->tableExists('vc_dispute')) {
			$table = $installer->getConnection()->newTable(
				$installer->getTable('vc_dispute')
			)
				->addColumn(
					'entity_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					[
						'identity' => true,
						'nullable' => false,
						'primary'  => true,
						'unsigned' => true,
					],
					'ID'
				)
				->addColumn(
					'app_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					['nullable' => true, 'default' => 0],
					'App Id'
				)
				->addColumn(
					'app_order_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					['nullable' => true, 'default' => 0],
					'App Order Id'
				)
				->addColumn(
					'vc_order_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					['nullable' => true, 'default' => 0],
					'VbhexCheckout Order Id'
				)
				->addColumn(
					'chain_order_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					['nullable' => true, 'default' => 0],
					'Chain Order Id'
				)
				->addColumn(
					'buyer_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					['nullable' => true, 'default' => 0],
					'Buyer Id'
				)
				->addColumn(
					'seller_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					['nullable' => true, 'default' => 0],
					'Seller Id'
				)
				->addColumn(
					'order_refund',
					\Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
					20,
					['nullable' => true, 'default' => 0],
					'Order Refund Amount'
				)
                ->addColumn(
					'order_refund_decm',
					\Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
					[16,6],
					['nullable' => true, 'default' => 0],
					'Order Refund Amount'
				)
                ->addColumn(
					'vote_price',
					\Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
					20,
					['nullable' => true, 'default' => 0],
					'Vote Price'
				)
				->addColumn(
					'min_votes',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					['nullable' => true, 'default' => 0],
					'Min Votes'
				)
				->addColumn(
					'min_vote_diff',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					['nullable' => true, 'default' => 0],
					'Min Vote Diffirence'
				)
				->addColumn(
					'refund_time',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					['nullable' => true, 'default' => 0],
					'Refund End Time On Chain'
				)
				->addColumn(
					'agree',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					['nullable' => false,'default' => 0],
					'Agree Voters Count'
				)
				->addColumn(
					'disagree',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					['nullable' => false,'default' => 0],
					'Disagree Voters Count'
				)
                ->addColumn(
					'tx_hash',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					77,
					['nullable => false'],
					'Trasaction Hash'
				)
				->addColumn(
					'last_tx_hash',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					77,
					['nullable => false'],
					'Last Trasaction Hash'
				)
				->addColumn(
					'status',
					\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
					2,
					['nullable' => false,'default' => 0],
					'0 refund not asked;1 refund asked;2 refund permitted by voters; 3 refund permitted by sellers; 4 refund denied by voters;5 refund refused by seller (Can vote);6 seller not responsed in time & buyer cashout;7 dispute in appealing.'
				)
				->setComment('VbhexCheckout Dispute Table');
			$installer->getConnection()->createTable($table);

			$installer->getConnection()->addIndex(
				$installer->getTable('vc_dispute'),
				$setup->getIdxName(
					$installer->getTable('vc_dispute'),
					['tx_hash','last_tx_hash'],
					\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
				),
				['tx_hash','last_tx_hash'],
				\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
			);
		}

		if (!$installer->tableExists('vc_dispute_discuss')) {
			$table = $installer->getConnection()->newTable(
				$installer->getTable('vc_dispute_discuss')
			)
				->addColumn(
					'entity_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					[
						'identity' => true,
						'nullable' => false,
						'primary'  => true,
						'unsigned' => true,
					],
					'ID'
				)
				->addColumn(
					'dispute_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					['nullable' => false, 'default' =>0],
					'Dispute Id'
				)
                ->addColumn(
					'author_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					['nullable' => false, 'default' => 0],
					'Author Id'
				)
				->addColumn(
					'author_type',
					\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
					1,
					['nullable' => false, 'default' =>0],
					'1 buyer, 2 seller, 3 voter'
				)
                ->addColumn(
					'detail',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					2048,
					['nullable => false'],
					'message detail'
				)
				->addColumn(
						'created_at',
						\Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
						null,
						['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
						'Created At'
				)
				->setComment('VbhexCheckout Dispute Discuss Table');
			$installer->getConnection()->createTable($table);

			$installer->getConnection()->addIndex(
				$installer->getTable('vc_dispute_discuss'),
				$setup->getIdxName(
					$installer->getTable('vc_dispute_discuss'),
					['detail'],
					\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
				),
				['detail'],
				\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
			);
		}

		if (!$installer->tableExists('vc_order')) {
			$table = $installer->getConnection()->newTable(
				$installer->getTable('vc_order')
			)
				->addColumn(
					'entity_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					[
						'identity' => true,
						'nullable' => false,
						'primary'  => true,
						'unsigned' => true,
					],
					'ID'
				)
				->addColumn(
					'app_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					['nullable' => false, 'default' =>0],
					'App Id'
				)
				->addColumn(
					'seller_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					['nullable' => false, 'default' =>0],
					'Seller Id'
				)
				->addColumn(
					'buyer_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					['nullable' => false, 'default' =>0],
					'Buyer Id'
				)
				->addColumn(
					'seller_wallet',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					55,
					['nullable => false'],
					'Seller Wallet'
				)
				->addColumn(
					'buyer_wallet',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					55,
					['nullable => false'],
					'Buyer Wallet'
				)
				->addColumn(
					'fiat_symbol',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					10,
					['nullable => false'],
					'Fiat Symbol'
				)
                ->addColumn(
					'fiat_amount',
					\Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
					[12,2],
					['nullable => false'],
					'message detail'
				)
				->addColumn(
					'selected_coin',
					\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
					5,
					['nullable' => false, 'default' =>0],
					'message detail'
				)
				->addColumn(
					'coin_address',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					55,
					['nullable => false'],
					'Coin Address'
				)
				->addColumn(
					'coin_symbol',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					10,
					['nullable => false'],
					'Coin Symbol'
				)
				->addColumn(
					'coin_decm',
					\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
					5,
					['nullable' => true, 'default' => 0],
					'Coin Decimals'
				)
				->addColumn(
					'coin_price',
					\Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
					[8,2],
					['nullable' => false, 'default'=>1.00],
					'Coin Price'
				)
				->addColumn(
					'coin_amount',
					\Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
					[16,6],
					['nullable' => false, 'default'=>0.00],
					'Coin Amount'
				)
				->addColumn(
					'paid_amount',
					\Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
					[16,6],
					['nullable' => false, 'default'=>0.00],
					'Paid Amount'
				)
				->addColumn(
					'order_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					['nullable' => false, 'default' =>0],
					'Magento Order Id'
				)
				->addColumn(
					'chain_order_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					['nullable' => false, 'default' =>0],
					'Chain Order Id'
				)
				->addColumn(
					'tx_hash',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					77,
					['nullable => false'],
					'Transaction Hash'
				)
				->addColumn(
					'cash_out_time',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					['nullable' => false, 'default' =>0],
					'Cash Out timestamp'
				)
				->addColumn(
					'status',
					\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
					1,
					['nullable' => false, 'default' =>0],
					'0 pending, 1 paid, 2 complete(can not dispute, can not vote)'
				)
				->addColumn(
						'created_at',
						\Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
						null,
						['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
						'Created At'
				)
				->setComment('VbhexCheckout Order Table');
			$installer->getConnection()->createTable($table);

			$installer->getConnection()->addIndex(
				$installer->getTable('vc_order'),
				$setup->getIdxName(
					$installer->getTable('vc_order'),
					['seller_wallet','buyer_wallet','fiat_symbol','coin_address','coin_symbol','tx_hash'],
					\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
				),
				['seller_wallet','buyer_wallet','fiat_symbol','coin_address','coin_symbol','tx_hash'],
				\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
			);
		}

		if (!$installer->tableExists('vc_user_coin_log')) {
			$table = $installer->getConnection()->newTable(
				$installer->getTable('vc_user_coin_log')
			)
				->addColumn(
					'entity_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					[
						'identity' => true,
						'nullable' => false,
						'primary'  => true,
						'unsigned' => true,
					],
					'ID'
				)
				->addColumn(
					'user_wallet',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					55,
					['nullable => false'],
					'User Wallet Address'
				)
                ->addColumn(
					'counterpart_wallet',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					55,
					['nullable => false'],
					'Counterpart Wallet Address'
				)
				->addColumn(
					'tx_hash',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					77,
					['nullable => false'],
					'Transaction Hash'
				)
                ->addColumn(
					'coin_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					['nullable' => false,'default'=>0],
					'Coin Id'
				)
                ->addColumn(
					'coin_symbol',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					12,
					['nullable => false'],
					'Coin Symbol'
				)
				->addColumn(
					'coin_decm',
					\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
					null,
					['nullable' => false,'default'=>0],
					'Coin Decimals'
				)
				->addColumn(
					'raw_coin_amount',
					\Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
					null,
					['nullable' => false,'default'=>0],
					'Raw Coin Amount'
				)
                ->addColumn(
					'coin_amount',
					\Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
					[16,6],
					['nullable' => false,'default'=>0],
					'Coin Amount'
				)
				->addColumn(
					'scenarios',
					\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
					1,
					['nullable' => false, 'default' => 1],
					'1 购买商品； 2 投票； 3 池子转入； 4 池子转出； 5 从池子提现；6 ETH购买VOTE'
				)
                ->addColumn(
					'diff_type',
					\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
					1,
					['nullable' => false, 'default' => 0],
					'1 池子余额增加, 2 池子余额减少 0 池子余额不变'
				)
				->addColumn(
					'app_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					['nullable' => false,'default'=>0],
					'App Id'
				)
				->addColumn(
					'chain_order_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					['nullable' => false,'default'=>0],
					'Chain Order Id'
				)
                ->addColumn(
					'app_order_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					['nullable' => false,'default'=>0],
					'App Order Id'
				)
				->addColumn(
						'created_at',
						\Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
						null,
						['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
						'Created At'
				)
				->setComment('VbhexCheckout Users Coins Log Table');
			$installer->getConnection()->createTable($table);

			$installer->getConnection()->addIndex(
				$installer->getTable('vc_user_coin_log'),
				$setup->getIdxName(
					$installer->getTable('vc_user_coin_log'),
					['user_wallet','tx_hash','coin_symbol'],
					\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
				),
				['user_wallet','tx_hash','coin_symbol'],
				\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
			);
		}

		if (!$installer->tableExists('vc_user_settings')) {
			$table = $installer->getConnection()->newTable(
				$installer->getTable('vc_user_settings')
			)
				->addColumn(
					'entity_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					[
						'identity' => true,
						'nullable' => false,
						'primary'  => true,
						'unsigned' => true,
					],
					'Entity ID'
				)
				->addColumn(
					'seller_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					['nullable' => false,'default'=>0],
					'Seller Id'
				)
				->addColumn(
					'store_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					['nullable' => false,'default'=>0],
					'Store Id'
				)
                ->addColumn(
					'seller_mod_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					['nullable' => false,'default'=>0],
					'Seller Mod Id'
				)
				->addColumn(
					'bsc_wallet_address',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					55,
					['nullable => false'],
					'bsc wallet address'
				)
				->addColumn(
					'eth_wallet_address',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					55,
					['nullable => false'],
					'eth wallet address'
				)
				->setComment('VbhexCheckout User Settings Table');
			$installer->getConnection()->createTable($table);

			$installer->getConnection()->addIndex(
				$installer->getTable('vc_user_settings'),
				$setup->getIdxName(
					$installer->getTable('vc_user_settings'),
					['bsc_wallet_address','eth_wallet_address'],
					\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
				),
				['bsc_wallet_address','eth_wallet_address'],
				\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
			);
		}

		if (!$installer->tableExists('vc_vote')) {
			$table = $installer->getConnection()->newTable(
				$installer->getTable('vc_vote')
			)
				->addColumn(
					'entity_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					[
						'identity' => true,
						'nullable' => false,
						'primary'  => true,
						'unsigned' => true,
					],
					'ID'
				)
				->addColumn(
					'app_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					['nullable' => false,'default'=>0],
					'App Id'
				)
				->addColumn(
					'chain_order_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					['nullable' => false,'default'=>0],
					'Chain Order Id'
				)
                ->addColumn(
					'voter_address',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					55,
					['nullable => false'],
					'Voter Address'
				)
				->addColumn(
					'vote_price',
					\Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
					null,
					['nullable' => false,'default'=>0],
					'Vote Price'
				)
				->addColumn(
					'is_agree',
					\Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
					null,
					['nullable' => false,'default'=>0],
					'1 agree; 0 disagree'
				)
				->addColumn(
					'tx_hash',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					77,
					['nullable => false'],
					'Transaction Hash'
				)
                ->addColumn(
					'status',
					\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
					null,
					['nullable' => false,'default'=>0],
					'0 not sure; 1 win; 2 lose; 3 seller agree voter is seller; 4 last agree; 5 last disagree; 6 seller agree voter is not seller;'
				)
                ->addColumn(
						'created_at',
						\Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
						null,
						['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
						'Created At'
				)
				->setComment('VbhexCheckout Vote Table');
			$installer->getConnection()->createTable($table);

			$installer->getConnection()->addIndex(
				$installer->getTable('vc_vote'),
				$setup->getIdxName(
					$installer->getTable('vc_vote'),
					['tx_hash'],
					\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
				),
				['tx_hash'],
				\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
			);
		}

        if (!$installer->tableExists('vc_buy_vote_log')) {
			$table = $installer->getConnection()->newTable(
				$installer->getTable('vc_buy_vote_log')
			)
				->addColumn(
					'entity_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					[
						'identity' => true,
						'nullable' => false,
						'primary'  => true,
						'unsigned' => true,
					],
					'ID'
				)
				->addColumn(
					'user_wallet',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					55,
					['nullable => false'],
					'User Wallet Address'
				)
				->addColumn(
					'tx_hash',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					77,
					['nullable => false'],
					'Buy Vote Transaction Hash'
				)
                ->addColumn(
					'eth_amount',
					\Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
					null,
					['nullable' => false,'default'=>0],
					'Buy Vote Paid ETH in wei'
				)
                ->addColumn(
					'eth_amount_decm',
					\Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
					[16,6],
					['nullable' => false,'default'=>0],
					'Buy Vote Paid ETH in decm'
				)
                ->addColumn(
					'given_gvote_tx_hash',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					77,
					['nullable => false'],
					'Given gVOTE Transaction Hash'
				)
                ->addColumn(
					'gvote_amount',
					\Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
					null,
					['nullable' => false,'default'=>0],
					'Given gVOTE amount in wei'
				)
                ->addColumn(
					'gvote_amount_decm',
					\Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
					[16,6],
					['nullable' => false,'default'=>0],
					'Given gVOTE amount in decm'
				)
                ->addColumn(
					'given_gusdt_tx_hash',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					77,
					['nullable => false'],
					'Give gUSDT Transaction Hash'
				)
                ->addColumn(
					'gusdt_amount',
					\Magento\Framework\DB\Ddl\Table::TYPE_BIGINT,
					null,
					['nullable' => false,'default'=>0],
					'given gUSDT amount in wei'
				)
                ->addColumn(
					'gusdt_amount_decm',
					\Magento\Framework\DB\Ddl\Table::TYPE_DECIMAL,
					[16,6],
					['nullable' => false,'default'=>0],
					'given gUSDT amount in decm'
				)
				->addColumn(
						'created_at',
						\Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
						null,
						['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
						'Created At'
				)
				->setComment('VbhexCheckout Buy Vote Log Table');
			$installer->getConnection()->createTable($table);

			$installer->getConnection()->addIndex(
				$installer->getTable('vc_buy_vote_log'),
				$setup->getIdxName(
					$installer->getTable('vc_buy_vote_log'),
					['user_wallet','tx_hash','given_gvote_tx_hash','given_gusdt_tx_hash'],
					\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
				),
				['user_wallet','tx_hash','given_gvote_tx_hash','given_gusdt_tx_hash'],
				\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
			);
		}

        if (!$installer->tableExists('vc_mods')) {
			$table = $installer->getConnection()->newTable(
				$installer->getTable('vc_mods')
			)
				->addColumn(
					'entity_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					[
						'identity' => true,
						'nullable' => false,
						'primary'  => true,
						'unsigned' => true,
					],
					'ID'
				)
                ->addColumn(
					'mod_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					['nullable' => false, 'default' => 0],
					'Mod Id'
				)
				->addColumn(
					'name',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					55,
					['nullable => false'],
					'Mod Name'
				)
                ->addColumn(
					'image',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					155,
					['nullable => false'],
					'Image URL'
				)
                ->addColumn(
					'external_url',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					155,
					['nullable' => true, 'default' => 0],
					'External Url'
				)
                ->addColumn(
					'description',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					550,
					['nullable => false'],
					'Contract Address'
				)
                ->addColumn(
					'attributes',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					555,
					['nullable => false'],
					'Mod Attributes'
				)
				->addColumn(
					'background_color',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					12,
					['nullable' => true],
					'Background Color'
				)
                ->addColumn(
					'animation_url',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					155,
					['nullable' => true, 'default' => 0],
					'External Url'
				)
                ->addColumn(
					'youtube_url',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					155,
					['nullable' => true, 'default' => 0],
					'Youtube Url'
				)
				->addColumn(
						'created_at',
						\Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
						null,
						['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
						'Created At'
				)
				->setComment('Vbhex Mods Table');
			$installer->getConnection()->createTable($table);

			$installer->getConnection()->addIndex(
				$installer->getTable('vc_mods'),
				$setup->getIdxName(
					$installer->getTable('vc_mods'),
					['name','image','description'],
					\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
				),
				['name','image','description'],
				\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
			);
		}

		$installer->endSetup();
	}
}
