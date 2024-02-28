<?php

declare(strict_types=1);

namespace Gubee\Integration\Model\ResourceModel;

use Gubee\Integration\Api\Data\InvoiceInterface;
use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Invoice extends AbstractDb
{
    /**
     * @inheritDoc
     */
    //phpcs:disable
    protected function _construct()
    {
        $this->_init(
            'gubee_integration_invoice',
            InvoiceInterface::INVOICE_ID
        );
    }
    //phpcs:enable
}
