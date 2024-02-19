<?php

declare(strict_types=1);

namespace Gubee\Integration\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Log extends AbstractDb
{
    /**
     * @inheritDoc
     */
    //phpcs:disable
    protected function _construct()
    {
        $this->_init('gubee_integration_log', 'log_id');
    }
    //phpcs:enable
}
