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

use Magento\Framework\Model\AbstractModel;
use Vbhex\Checkout\Api\Data\CoinInterface;
use Vbhex\Checkout\Api\CoinRepositoryInterface;
use Vbhex\Checkout\Model\ResourceModel\Coin\CollectionFactory;

/**
 * Dservice Coin Model.
 *
 */
class CoinRepository implements CoinRepositoryInterface
{
    /**
     * @var CoinFactory
     */
    protected $_coinFactory;


    /**
     * Initialize resource model.
     */
    public function __construct(
        CollectionFactory $coinFactory
    )
    {
        $this->_coinFactory = $coinFactory;
    }



    public function getActiveCoins()
    {
        $coinCollection = $this->_coinFactory->create()
        ->addFieldToSelect('*')
        ->addFieldToFilter('is_valid', true);

        $data = array();

        foreach($coinCollection->getItems() as $coin) {
            $data[]             =   [
                'id'                =>      $coin->getId(),
                'name'              =>      $coin->getName(),
                'symbol'            =>      $coin->getSymbol(),
                'decm'              =>      $coin->getDecm(),
                'contract_address'  =>      $coin->getContractAddress(),
                'price'             =>      $coin->getPrice(),
                'logo'              =>      $coin->getLogo(),
                'created_at'        =>      $coin->getCreatedAt()
            ];
        }

        echo json_encode([
            'message' => 'OK',
            'status' => 200,
            'result' => $data
        ]);
        exit;
    }


}
