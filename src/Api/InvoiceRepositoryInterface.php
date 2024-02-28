<?php

declare(strict_types=1);

namespace Gubee\Integration\Api;

use Magento\Framework\Api\SearchCriteriaInterface;

interface InvoiceRepositoryInterface
{

    /**
     * Save Invoice
     * @param \Gubee\Integration\Api\Data\InvoiceInterface $invoice
     * @return \Gubee\Integration\Api\Data\InvoiceInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function save(
        \Gubee\Integration\Api\Data\InvoiceInterface $invoice
    );

    /**
     * Retrieve Invoice
     * @param string $invoiceId
     * @return \Gubee\Integration\Api\Data\InvoiceInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function get($invoiceId);

    /**
     * Retrieve Invoice matching the specified criteria.
     * @param \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
     * @return \Gubee\Integration\Api\Data\InvoiceSearchResultsInterface
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function getList(
        \Magento\Framework\Api\SearchCriteriaInterface $searchCriteria
    );

    /**
     * Delete Invoice
     * @param \Gubee\Integration\Api\Data\InvoiceInterface $invoice
     * @return bool true on success
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function delete(
        \Gubee\Integration\Api\Data\InvoiceInterface $invoice
    );

    /**
     * Delete Invoice by ID
     * @param string $invoiceId
     * @return bool true on success
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    public function deleteById($invoiceId);
}
