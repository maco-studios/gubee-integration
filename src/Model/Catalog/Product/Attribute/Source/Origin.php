<?php

declare(strict_types=1);

namespace Gubee\Integration\Model\Catalog\Product\Attribute\Source;

use Gubee\SDK\Interfaces\Catalog\ProductInterface;
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

use function __;
use function str_replace;
use function strtolower;
use function ucfirst;

class Origin extends AbstractSource
{
    /**
     * @return array
     */
    public function getAllOptions()
    {
        if ($this->_options) {
            return $this->_options;
        }

        $options = [
            ProductInterface::FOREIGN_ACQUIRED_IN_THE_INTERNAL_MARKET_WITHOUT_SIMILAR,
            ProductInterface::FOREIGN_DIRECTION_IMPORTATION,
            ProductInterface::FOREIGN_INTERNAL_MARKET,
            ProductInterface::FOREIGN_WITHOUT_NATIONAL_SIMILAR,
            ProductInterface::NATIONAL,
            ProductInterface::NATIONAL_CONFORMITY_ADJUSTMENTS,
            ProductInterface::NATIONAL_IMPORTS_PLUS_40_PERCENT,
            ProductInterface::NATIONAL_IMPORTS_PLUS_70_PERCENT,
            ProductInterface::NATIONAL_IMPORT_MINUS_40_PERCENT,
        ];

        foreach ($options as $option) {
            $this->_options[] = [
                'label' => __($this->getLabel($option)),
                'value' => $option,
            ];
        }

        return $this->_options;
    }

    /**
     * Get label for option value
     */
    public function getLabel(string $value): string
    {
        $value = str_replace(
            ["_", " PERCENT"],
            [" ", "%"],
            $value
        );

        $value = strtolower($value);

        return ucfirst($value);
    }
}
