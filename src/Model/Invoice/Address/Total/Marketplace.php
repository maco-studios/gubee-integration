<?php

namespace Gubee\Integration\Model\Invoice\Address\Total;

/**
 * Class FinanceCost
 *
 * @package MercadoPago\Core\Model\Creditmemo
 */
class Marketplace extends \Magento\Sales\Model\Order\Total\AbstractTotal {
    /**
     * @param \Magento\Sales\Model\Order\Creditmemo $creditmemo
     * @return $this
     */
    public function collect(\Magento\Sales\Model\Order\Creditmemo $creditmemo) {
        $order = $creditmemo->getOrder();
        $amount = $order->getGubeeMarketplaceTotalAmount();
        $baseAmount = $order->getBaseGubeeMarketplaceTotalAmount();

        if ($amount) {
            $creditmemo->setGubeeMarketplaceTotalAmount($amount);
            $creditmemo->getBaseGubeeMarketplaceTotalAmount($baseAmount);
        }

        return $this;
    }
}
