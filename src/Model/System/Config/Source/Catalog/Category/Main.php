<?php

declare(strict_types=1);

namespace Gubee\Integration\Model\System\Config\Source\Catalog\Category;

use Magento\Framework\Option\ArrayInterface;

use function __;
use function array_column;
use function array_combine;

class Main implements ArrayInterface
{
    public const DEEPER = 'deeper';
    public const HIGHER = 'higher';

    /**
     * @return array
     */
    public function toOptionArray(): array
    {
        return [
            [
                'label' => __("Deeper"),
                'value' => self::DEEPER,
            ],
            [
                'label' => __("Higher"),
                'value' => self::HIGHER,
            ],
        ];
    }

    public function toArray(): array
    {
        return array_combine(
            array_column($this->toOptionArray(), 'value'),
            array_column($this->toOptionArray(), 'label')
        );
    }
}
