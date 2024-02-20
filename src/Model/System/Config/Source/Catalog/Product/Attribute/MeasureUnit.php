<?php

declare(strict_types=1);

namespace Gubee\Integration\Model\System\Config\Source\Catalog\Product\Attribute;

use Gubee\SDK\Interfaces\Catalog\Product\Attribute\Dimension\MeasureInterface;
use Magento\Framework\Option\ArrayInterface;
use ReflectionClass;

use function __;
use function strtolower;
use function ucfirst;

class MeasureUnit implements ArrayInterface
{
    /**
     * @return mixed
     */
    public function toOptionArray()
    {
        $options   = [];
        $constants = (new ReflectionClass(MeasureInterface::class))
            ->getConstants();
        foreach ($constants as $constant) {
            $options[] = [
                'label' => __(ucfirst(strtolower($constant))),
                'value' => $constant,
            ];
        }

        return $options;
    }
}
