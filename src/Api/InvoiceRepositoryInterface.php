<?php

declare(strict_types=1);

namespace Gubee\Integration\Api;

use Gubee\Integration\Api\Data\InvoiceInterface;
use Gubee\Integration\Api\Data\InvoiceSearchResultsInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Exception\NoSuchEntityException;

interface InvoiceRepositoryInterface
{
    /**
     * Save Invoice
     *
     * @return InvoiceInterface
     * @throws LocalizedException
     */
    public function save(
        InvoiceInterface $invoice
    );

    /**
     * Retrieve Invoice
     *
     * @param string $invoiceId
     * @return InvoiceInterface
     * @throws LocalizedException
     */
    public function get($invoiceId);

    /**
     * Retrieve Invoice matching the specified criteria.
     *
     * @return InvoiceSearchResultsInterface
     * @throws LocalizedException
     */
    public function getList(
        SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Invoice
     *
     * @return bool true on success
     * @throws LocalizedException
     */
    public function delete(
        InvoiceInterface $invoice
    );

    /**
     * Delete Invoice by ID
     *
     * @param string $invoiceId
     * @return bool true on success
     * @throws NoSuchEntityException
     * @throws LocalizedException
     */
    public function deleteById($invoiceId);
}
