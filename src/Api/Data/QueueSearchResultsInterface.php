<?php

declare(strict_types=1);

namespace Gubee\Integration\Api\Data;

use Gubee\Integration\Api\Data\QueueInterface;
use Magento\Framework\Api\SearchResultsInterface;

interface QueueSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get Queue list.
     *
     * @return QueueInterface[]
     */
    public function getItems();

    /**
     * Set process list.
     *
     * @param QueueInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
