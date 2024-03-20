<?php

declare(strict_types=1);

namespace Gubee\Integration\Model\Payment;

use Magento\Payment\Model\Method\AbstractMethod;
use Magento\Quote\Api\Data\CartInterface;

class Gubee extends AbstractMethod
{
    /**
     * Don't show the payment method on checkout page
     *
     * @var bool
     */
    protected $_canUseCheckout = false;
    protected $_code           = "gubee";
    protected $_isOffline      = true;

    public function isAvailable(
        ?CartInterface $quote = null
    ) {
        return parent::isAvailable($quote);
    }
}
