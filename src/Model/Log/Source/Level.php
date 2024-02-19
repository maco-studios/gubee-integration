<?php

declare(strict_types=1);

namespace Gubee\Integration\Model\Log\Source;

use Gubee\Integration\Api\Data\LogInterface;
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

use function __;

class Level extends AbstractSource
{
    /**
     * @return array
     */
    public function getAllOptions()
    {
        return [
            ['label' => __('Emergency'), 'value' => LogInterface::EMERGENCY],
            ['label' => __('Alert'), 'value' => LogInterface::ALERT],
            ['label' => __('Critical'), 'value' => LogInterface::CRITICAL],
            ['label' => __('Error'), 'value' => LogInterface::ERROR],
            ['label' => __('Warning'), 'value' => LogInterface::WARNING],
            ['label' => __('Notice'), 'value' => LogInterface::NOTICE],
            ['label' => __('Info'), 'value' => LogInterface::INFO],
            ['label' => __('Debug'), 'value' => LogInterface::DEBUG],
        ];
    }
}
