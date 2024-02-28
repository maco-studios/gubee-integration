<?php

declare(strict_types=1);

namespace Gubee\Integration\Model\ResourceModel\Invoice;

use Gubee\Integration\Api\Data\InvoiceInterface;
use Gubee\Integration\Model\Invoice;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    //phpcs:disable
    /** @inheritDoc */
    protected $_idFieldName = InvoiceInterface::INVOICE_ID;

    /**
     * @inheritDoc
     */
    protected function _construct()
    {
        $this->_init(
            Invoice::class,
            \Gubee\Integration\Model\ResourceModel\Invoice::class
        );
    }
    //phpcs:enable
}
