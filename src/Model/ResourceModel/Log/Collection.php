<?php

declare(strict_types=1);

namespace Gubee\Integration\Model\ResourceModel\Log;

use Gubee\Integration\Model\Log;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    /** @inheritDoc */
    //phpcs:disable
    protected $_idFieldName = 'log_id';

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            Log::class,
                \Gubee\Integration\Model\ResourceModel\Log::class
        );
    }
    //phpcs:enable
}
