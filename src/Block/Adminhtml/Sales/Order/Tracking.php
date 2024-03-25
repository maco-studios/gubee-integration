<?php

declare (strict_types = 1);

namespace Gubee\Integration\Block\Adminhtml\Sales\Order;

use Gubee\Integration\Model\ResourceModel\Invoice\CollectionFactory;
use Magento\Shipping\Block\Adminhtml\Order\Tracking as OrderTracking;

class Tracking extends OrderTracking {

    protected CollectionFactory $invoiceCollectionFactory;

    public function __construct(
        \Magento\Backend\Block\Template\Context $context,
        \Magento\Shipping\Model\Config $shippingConfig,
        \Magento\Framework\Registry $registry,
        CollectionFactory $invoiceCollectionFactory,
        array $data = []
    ) {
        $this->invoiceCollectionFactory = $invoiceCollectionFactory;
        parent::__construct($context, $shippingConfig, $registry, $data);
    }

    protected function _construct() {
        parent::_construct();
        if ($this->getOrder()->getPayment()->getMethod() == 'gubee') {
            $this->_template = 'Gubee_Integration::order/tracking.phtml';
        }
    }
    public function getOrder() {
        return $this->_coreRegistry->registry('current_shipment')
            ->getOrder();
    }

    public function getOrderId() {
        return $this->getOrder()->getId();
    }

    public function getInvoices() {
        return $this->invoiceCollectionFactory->create()
            ->addFieldToFilter('order_id', $this->getOrderId());
    }

}
