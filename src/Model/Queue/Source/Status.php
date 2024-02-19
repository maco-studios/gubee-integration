<?php

declare(strict_types=1);

namespace Gubee\Integration\Model\Queue\Source;

use Gubee\Integration\Api\Data\QueueInterface;
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

use function __;

class Status extends AbstractSource
{
    /**
     * @return array
     */
    public function getAllOptions()
    {
        return [
            [
                'label' => __('Pending'),
                'value' => QueueInterface::STATUS_PENDING,
            ],
            [
                'label' => __('Running'),
                'value' => QueueInterface::STATUS_RUNNING,
            ],
            [
                'label' => __('Stopped'),
                'value' => QueueInterface::STATUS_STOPPED,
            ],
            [
                'label' => __('Failed'),
                'value' => QueueInterface::STATUS_FAILED,
            ],
            [
                'label' => __('Error'),
                'value' => QueueInterface::STATUS_ERROR,
            ],
            [
                'label' => __('Success'),
                'value' => QueueInterface::STATUS_SUCCESS,
            ],
        ];
    }
}
