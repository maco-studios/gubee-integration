<?php

declare(strict_types=1);

namespace Gubee\Integration\Model\System\Config\Source\Log;

use Gubee\Integration\Api\Data\LogInterface;
use Magento\Framework\Option\ArrayInterface;

use function __;

class Level implements ArrayInterface
{
    /**
     * Return array of options as value-label pairs
     *
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            [
                'value' => LogInterface::DEBUG,
                'label' => __('Debug'),
            ],
            [
                'value' => LogInterface::INFO,
                'label' => __('Info'),
            ],
            [
                'value' => LogInterface::NOTICE,
                'label' => __('Notice'),
            ],
            [
                'value' => LogInterface::WARNING,
                'label' => __('Warning'),
            ],
            [
                'value' => LogInterface::ERROR,
                'label' => __('Error'),
            ],
            [
                'value' => LogInterface::CRITICAL,
                'label' => __('Critical'),
            ],
            [
                'value' => LogInterface::ALERT,
                'label' => __('Alert'),
            ],
            [
                'value' => LogInterface::EMERGENCY,
                'label' => __('Emergency'),
            ],
        ];
    }
}
