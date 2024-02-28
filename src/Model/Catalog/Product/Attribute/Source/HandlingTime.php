<?php

declare(strict_types=1);

namespace Gubee\Integration\Model\Catalog\Product\Attribute\Source;

use Gubee\SDK\Api\Catalog\Product\Attribute\Dimension\UnitTimeInterface;
use Magento\Eav\Model\Entity\Attribute\Source\AbstractSource;

use function __;
use function str_replace;
use function strtolower;
use function ucfirst;

class HandlingTime extends AbstractSource
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
            UnitTimeInterface::DAYS,
            UnitTimeInterface::HOURS,
            UnitTimeInterface::MONTH,
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
            "_",
            " ",
            $value
        );

        $value = strtolower($value);

        return ucfirst($value);
    }
}
