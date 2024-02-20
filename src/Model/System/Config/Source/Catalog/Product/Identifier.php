<?php

declare(strict_types=1);

namespace Gubee\Integration\Model\System\Config\Source\Catalog\Product;

use Magento\Framework\Option\ArrayInterface;

use function __;

class Identifier implements ArrayInterface
{
    /**
     * @return array
     */
    public function toOptionArray()
    {
        return [
            [
                'label' => __('-- Please Select --'),
                'value' => '',
            ],
            [
                'label' => __('Product ID'),
                'value' => 'entity_id',
            ],
            [
                'label' => __('Product SKU'),
                'value' => 'sku',
            ],
        ];
    }
}
