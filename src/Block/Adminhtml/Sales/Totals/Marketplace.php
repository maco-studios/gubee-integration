<?php

declare (strict_types = 1);

namespace Gubee\Integration\Block\Adminhtml\Sales\Totals;

class Marketplace extends \Magento\Framework\View\Element\Template {
    protected $_template = 'Gubee_Integration::order/totals/marketplace.phtml';

    public function initTotals() {
        $parent = $this->getParentBlock();
        $source = $parent->getSource();
        $total = new \Magento\Framework\DataObject(
            [
                'code' => 'gubee_marketplace_total',
                'field' => 'gubee_marketplace_total',
                'value' => $this->getParentBlock()->getSource()->getGubeeMarketplaceTotalAmount(),
                'label' => __('Marketplace Total'),
            ]
        );
        $parent->addTotal($total, 'subtotal');
        return $this;
    }
}
