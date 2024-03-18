<?php


declare(strict_types=1);

namespace Gubee\Integration\Api\Data\Message;

interface DetailSearchResultsInterface extends \Magento\Framework\Api\SearchResultsInterface
{

    /**
     * Get Detail list.
     * @return \Gubee\Integration\Api\Data\Message\DetailInterface[]
     */
    public function getItems();

    /**
     * Set level list.
     * @param \Gubee\Integration\Api\Data\Message\DetailInterface[] $items
     * @return $this
     */
    public function setItems(array $items);
}