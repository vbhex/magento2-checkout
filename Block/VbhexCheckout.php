<?php

namespace Vbhex\Checkout\Block;

/*
 * Vbhex Checkout Landing Page Block
 */

class VbhexCheckout extends \Magento\Framework\View\Element\Template
{

    public function __construct(
        \Magento\Catalog\Block\Product\Context $context,
        array $data = []
    ) {
        parent::__construct($context, $data);
    }

    /**
     * Prepare HTML content.
     *
     * @return string
     */
    public function getCmsFilterContent($value = '')
    {
        return $this->filterProvider->getPageFilter()->filter($value);
    }

    public function getTopDisputes()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $sql = "select
                dd.entity_id as dd_entity_id,
                dd.app_id,
                dd.app_order_id,
                dd.vc_order_id,
                dd.chain_order_id,
                dd.order_refund,
                dd.order_refund_decm,
                dd.moda_id,
                dd.modb_id,
                dd.moda_vote,
                dd.modb_vote,
                dd.status,
                do.paid_amount,
                do.coin_symbol,
                do.coin_decm
                from vc_dispute as dd
                left join vc_order as do
                on dd.vc_order_id = do.entity_id
                where dd.status = 7
                order by do.created_at asc
                limit 10";
        $result = $connection->fetchAll($sql);

        return $result;
    }

    public function getETHPrice()
    {
        $objectManager = \Magento\Framework\App\ObjectManager::getInstance();
        $resource = $objectManager->get('Magento\Framework\App\ResourceConnection');
        $connection = $resource->getConnection();
        $sql = "select
                price
                from vc_coins
                where symbol = 'ETH'";
        $result = $connection->fetchAll($sql);
        $price = 10000;
        if(isset($result[0]['price'])&&is_numeric($result[0]['price'])){
            $price = $result[0]['price'];
        }
        return $price;
    }

    /**
     * Get dispute detail view URL
     *
     * @param object $orderId
     * @return string
     */
    public function getDetailUrl($orderId)
    {
        return $this->getUrl('vc/dispute/detail', ['order' => $orderId]);
    }

    public function getMoreUrl()
    {
        return $this->getUrl('vc/dispute/lists', ['status' => 7]);
    }

    /**
     * Get message for no logs.
     *
     * @return \Magento\Framework\Phrase
     * @since 102.1.0
     */
    public function getEmptyLogsMessage()
    {
        return __('There is no disputes to vote.');
    }
}
