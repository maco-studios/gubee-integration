<?php

declare(strict_types=1);

namespace Gubee\Integration\Model;

use Gubee\Integration\Api\Data\InvoiceInterface;
use Magento\Framework\Model\AbstractModel;

class Invoice extends AbstractModel implements InvoiceInterface
{
    /**
     * @inheritDoc
     */
    //phpcs:disable
    public function _construct()
    {
        $this->_init(\Gubee\Integration\Model\ResourceModel\Invoice::class);
    }
    //phpcs:enable

    /**
     * @inheritDoc
     */
    public function getInvoiceId()
    {
        return $this->getData(self::INVOICE_ID);
    }

    /**
     * @inheritDoc
     */
    public function setInvoiceId($invoiceId)
    {
        return $this->setData(self::INVOICE_ID, $invoiceId);
    }

    /**
     * @inheritDoc
     */
    public function getDanfeLink()
    {
        return $this->getData(self::DANFELINK);
    }

    /**
     * @inheritDoc
     */
    public function setDanfeLink($danfeLink)
    {
        return $this->setData(self::DANFELINK, $danfeLink);
    }

    /**
     * @inheritDoc
     */
    public function getDanfeXml()
    {
        return $this->getData(self::DANFEXML);
    }

    /**
     * @inheritDoc
     */
    public function setDanfeXml($danfeXml)
    {
        return $this->setData(self::DANFEXML, $danfeXml);
    }

    /**
     * @inheritDoc
     */
    public function getIssueDate()
    {
        return $this->getData(self::ISSUEDATE);
    }

    /**
     * @inheritDoc
     */
    public function setIssueDate($issueDate)
    {
        return $this->setData(self::ISSUEDATE, $issueDate);
    }

    /**
     * @inheritDoc
     */
    public function getKey()
    {
        return $this->getData(self::KEY);
    }

    /**
     * @inheritDoc
     */
    public function setKey($key)
    {
        return $this->setData(self::KEY, $key);
    }

    /**
     * @inheritDoc
     */
    public function getLine()
    {
        return $this->getData(self::LINE);
    }

    /**
     * @inheritDoc
     */
    public function setLine($line)
    {
        return $this->setData(self::LINE, $line);
    }

    /**
     * @inheritDoc
     */
    public function getNumber()
    {
        return $this->getData(self::NUMBER);
    }

    /**
     * @inheritDoc
     */
    public function setNumber($number)
    {
        return $this->setData(self::NUMBER, $number);
    }

    /**
     * Get created_at
     *
     * @return string|null
     */
    public function getCreatedAt()
    {
        return $this->getData(self::CREATED_AT);
    }

    /**
     * Set created_at
     *
     * @param string $createdAt
     * @return \Gubee\Integration\Invoice\Api\Data\InvoiceInterface
     */
    public function setCreatedAt($createdAt)
    {
        return $this->setData(self::CREATED_AT, $createdAt);
    }

    /**
     * Get updated_at
     *
     * @return string|null
     */
    public function getUpdatedAt()
    {
        return $this->getData(self::UPDATED_AT);
    }

    /**
     * Set updated_at
     *
     * @param string $updatedAt
     * @return \Gubee\Integration\Invoice\Api\Data\InvoiceInterface
     */
    public function setUpdatedAt($updatedAt)
    {
        return $this->setData(self::UPDATED_AT, $updatedAt);
    }
}
