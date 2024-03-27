<?php

declare(strict_types=1);

namespace MercadoPago\Core\Model\Creditmemo;

use Magento\Sales\Model\Order\Creditmemo;
use Magento\Sales\Model\Order\Total\AbstractTotal;

class Marketplace extends AbstractTotal
{
    /**
     * @return $this
     */
    public function collect(Creditmemo $creditmemo)
    {
        $order      = $creditmemo->getOrder();
        $amount     = $order->getGubeeMarketplaceTotalAmount();
        $baseAmount = $order->getBaseGubeeMarketplaceTotalAmount();

        if ($amount) {
            $creditmemo->setGubeeMarketplaceTotalAmount($amount);
            $creditmemo->getBaseGubeeMarketplaceTotalAmount($baseAmount);
        }

        return $this;
    }
}
