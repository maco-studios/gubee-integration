<?php

declare(strict_types=1);

namespace Gubee\Integration\Api\Data;

interface InvoiceSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Invoice list.
     * @return \Gubee\Integration\Api\Data\InvoiceInterface[]
     */
    public function getItems();

    /**
     * Set danfeLink list.
     * @param \Gubee\Integration\Api\Data\InvoiceInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}
