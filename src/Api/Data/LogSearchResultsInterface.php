<?php

declare(strict_types=1);

namespace Gubee\Integration\Api\Data;

use Gubee\Integration\Api\Data\LogInterface;
use Magento\Framework\Api\SearchResultsInterface;

interface LogSearchResultsInterface extends SearchResultsInterface
{
    /**
     * Get Log list.
     *
     * @return LogInterface[]
     */
    public function getItems();

    /**
     * Set message list.
     *
     * @param LogInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
