<?php

declare(strict_types=1);

namespace Gubee\Integration\Model\System\Config\Source\Catalog\Product;

use Magento\Catalog\Model\Product;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Eav\Model\Entity\Type;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection;
use Magento\Framework\Option\ArrayInterface;

use function __;
use function strcmp;
use function usort;

abstract class AbstractAttribute implements ArrayInterface
{
    protected Type $entityType;

    private Attribute $attributeResource;

    public function __construct(
        Attribute $attributeResource,
        Type $entityType
    ) {
        $this->attributeResource = $attributeResource;
        $this->entityType        = $entityType;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options = [
            [
                'value' => '',
                'label' => __('-- Please Select --'),
            ],
        ];
        foreach ($this->getCollection() as $attribute) {
            $options[] = [
                'value' => $attribute->getAttributeCode(),
                'label' => $attribute->getFrontendLabel(),
            ];
        }
        $options[] = [
            'value' => 'entity_id',
            'label' => __("Product ID"),
        ];
        usort($options, function ($a, $b) {
            return strcmp((string) $a['label'], (string) $b['label']);
        });
        return $options;
    }

    protected function getCollection(): Collection
    {
        $catalogAttributeSetId = $this->entityType
            ->loadByCode(
                Product::ENTITY
            )->getId();
        return $this->attributeResource->getCollection()
            ->addFieldToFilter(
                'entity_type_id',
                $catalogAttributeSetId
            );
    }
}
