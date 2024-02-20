<?php

declare(strict_types=1);

namespace Gubee\Integration\Model\System\Config\Source\Catalog\Product\Attribute;

use Gubee\Integration\Model\System\Config\Source\Catalog\Product\AbstractAttribute;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection;

class Integer extends AbstractAttribute
{
    protected function getCollection(): Collection
    {
        return parent::getCollection()
            ->addFieldToFilter(
                'frontend_input',
                'price'
            );
    }
}
