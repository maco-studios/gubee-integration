<?php

declare(strict_types=1);

namespace Gubee\Integration\Model\ResourceModel\Queue;

use Gubee\Integration\Model\Queue;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /** @inheritDoc */
    //phpcs:disable
    protected $_idFieldName = 'queue_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            Queue::class,
            \Gubee\Integration\Model\ResourceModel\Queue::class
        );
    }
    //phpcs:enable
}
