<?php

declare (strict_types = 1);

namespace Gubee\Integration\Plugin\Sales\Order\Grid\Marketplace;
use Magento\Framework\Message\ManagerInterface as MessageManager;
use Magento\Sales\Model\ResourceModel\Order\Grid\Collection as SalesOrderGridCollection;

class Column {
    protected MessageManager $messageManager;
    protected SalesOrderGridCollection $collection;

    public function __construct(
        MessageManager $messageManager,
        SalesOrderGridCollection $collection
    ) {
        $this->messageManager = $messageManager;
        $this->collection = $collection;
    }

    public function aroundGetReport(
        \Magento\Framework\View\Element\UiComponent\DataProvider\CollectionFactory $subject,
        \Closure $proceed,
        $requestName
    ) {
        $result = $proceed($requestName);
        if ($requestName == 'sales_order_grid_data_source') {
            if ($result instanceof $this->collection) {
                $select = $result->getSelect();
                $select->join(
                    ["gubee_order" => "gubee_integration_order"],
                    'main_table.entity_id = gubee_order.order_id ',
                    array('gubee_marketplace', 'gubee_order_id')
                )->distinct();
                // $select->joinRight(
                //     ["gubee_order" => "gubee_integration_order"],
                //     'main_table.entity_id = gubee_order.order_id ',
                //     array('gubee_marketplace', 'gubee_order_id')
                // );
            }
        }
        return $result;
    }
}
