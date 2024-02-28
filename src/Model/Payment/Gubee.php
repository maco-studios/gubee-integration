<?php

declare(strict_types=1);

namespace Gubee\Integration\Model\Payment;

use Magento\Payment\Model\Method\AbstractMethod;
use Magento\Quote\Api\Data\CartInterface;

class Gubee extends AbstractMethod
{
    //phpcs:disable
    /**
     * Don't show the payment method on checkout page
     *
     * @var bool
     */
    protected $_canUseCheckout = false;
    protected $_code           = "gubee";
    protected $_isOffline      = true;
    //phpcs:enable

    /**
     * Check if the payment method is available
     *
     * @return bool
     */
    public function isAvailable(
        ?CartInterface $quote = null
    ) {
        return parent::isAvailable($quote);
    }
}
