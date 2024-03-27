<?php

declare(strict_types=1);

namespace Gubee\Integration\Model\Quote\Address\Total;

use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address\Total;
use Magento\Quote\Model\Quote\Address\Total\AbstractTotal;

use function __;

class Marketplace extends AbstractTotal
{
    protected $_code = 'gubee_marketplace_total';

    public function collect(
        Quote $quote,
        ShippingAssignmentInterface $shippingAssignment,
        Total $total
    ) {
        $address = $shippingAssignment->getShipping()->getAddress();
        parent::collect($quote, $shippingAssignment, $total);

        $grandTotal = $total->getGrandTotal();
        $shipping   = $address->getShippingAmount();
        $tmpTotal   = $grandTotal - $shipping;

        $address->setGubeeMarketplaceTotalAmount($tmpTotal);
        $address->setBaseGubeeMarketplaceTotalAmount($tmpTotal);

        $total->setGubeeMarketplaceTotalDescription(__('Marketplace Total'));
        $total->setGubeeMarketplaceTotalAmount($tmpTotal);
        $total->setBaseGubeeMarketplaceTotalAmount($tmpTotal);

        $total->setTotalAmount($this->getCode(), $tmpTotal);
        $total->setBaseTotalAmount($this->getCode(), $tmpTotal);

        return $this;
    }

    public function fetch(
        Quote $quote,
        Total $total
    ) {
        return [
            'code'  => $this->getCode(),
            'title' => __('Marketplace Total'),
            'value' => $this->getGubeeMarketplaceTotalAmount(),
        ];
    }

    public function getLabel()
    {
        return __('Marketplace Total');
    }

    public function getLabelTitle()
    {
        return __('Marketplace Total');
    }
}
