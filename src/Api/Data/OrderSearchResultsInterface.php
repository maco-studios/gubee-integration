<?php

declare (strict_types = 1);

namespace Gubee\Integration\Api\Data;

interface OrderSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface {

    /**
     * Get Order list.
     * @return \Gubee\Integration\Api\Data\OrderInterface[]
     */
    public function getItems();

    /**
     * Set order_id list.
     * @param \Gubee\Integration\Api\Data\OrderInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}