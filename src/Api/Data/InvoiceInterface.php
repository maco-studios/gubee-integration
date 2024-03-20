<?php

declare(strict_types=1);

namespace Gubee\Integration\Api\Data;

interface InvoiceInterface
{
    const INVOICE_ID = 'invoice_id';
    const NUMBER     = 'number';
    const DANFEXML   = 'danfeXml';
    const LINE       = 'line';
    const KEY        = 'key';
    const DANFELINK  = 'danfeLink';
    const ISSUEDATE  = 'issueDate';
    const ORDER_ID   = 'order_id';

    /**
     * Get invoice_id
     *
     * @return string|null
     */
    public function getInvoiceId();

    /**
     * Set invoice_id
     *
     * @param string $invoiceId
     * @return \Gubee\Integration\Invoice\Api\Data\InvoiceInterface
     */
    public function setInvoiceId($invoiceId);

    /**
     * Get danfeLink
     *
     * @return string|null
     */
    public function getDanfeLink();

    /**
     * Set danfeLink
     *
     * @param string $danfeLink
     * @return \Gubee\Integration\Invoice\Api\Data\InvoiceInterface
     */
    public function setDanfeLink($danfeLink);

    /**
     * Get danfeXml
     *
     * @return string|null
     */
    public function getDanfeXml();

    /**
     * Set danfeXml
     *
     * @param string $danfeXml
     * @return \Gubee\Integration\Invoice\Api\Data\InvoiceInterface
     */
    public function setDanfeXml($danfeXml);

    /**
     * Get issueDate
     *
     * @return string|null
     */
    public function getIssueDate();

    /**
     * Set issueDate
     *
     * @param string $issueDate
     * @return \Gubee\Integration\Invoice\Api\Data\InvoiceInterface
     */
    public function setIssueDate($issueDate);

    /**
     * Get key
     *
     * @return string|null
     */
    public function getKey();

    /**
     * Set key
     *
     * @param string $key
     * @return \Gubee\Integration\Invoice\Api\Data\InvoiceInterface
     */
    public function setKey($key);

    /**
     * Get line
     *
     * @return string|null
     */
    public function getLine();

    /**
     * Set line
     *
     * @param string $line
     * @return \Gubee\Integration\Invoice\Api\Data\InvoiceInterface
     */
    public function setLine($line);

    /**
     * Get number
     *
     * @return string|null
     */
    public function getNumber();

    /**
     * Set number
     *
     * @param string $number
     * @return \Gubee\Integration\Invoice\Api\Data\InvoiceInterface
     */
    public function setNumber($number);

    /**
     * Get order_id
     *
     * @return string|null
     */
    public function getOrderId();

    /**
     * Set order_id
     *
     * @param string $orderId
     * @return \Gubee\Integration\Invoice\Api\Data\InvoiceInterface
     */
    public function setOrderId($orderId);
}
