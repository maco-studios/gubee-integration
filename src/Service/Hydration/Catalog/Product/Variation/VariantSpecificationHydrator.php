<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product\Variation;

use Gubee\Integration\Helper\Config;
use Gubee\Integration\Model\ResourceModel\Catalog\Attribute\CollectionFactory;
use Gubee\SDK\Model\Catalog\Product\Attribute;
use Gubee\SDK\Model\Catalog\Product\Attribute\Value;
use Magento\Eav\Model\AttributeSearchResults;
use Magento\Framework\ObjectManagerInterface;

use function is_array;

class VariantSpecificationHydrator extends AbstractHydrator
{
    protected AttributeSearchResults $attributeCollection;
    protected ObjectManagerInterface $objectManager;

    public function __construct(
        CollectionFactory $attributeCollectionFactory,
        ObjectManagerInterface $objectManager,
        Config $config
    ) {
        $this->attributeCollection = $attributeCollectionFactory->create();
        $this->objectManager       = $objectManager;
        parent::__construct($config);
    }

    /**
     * Extract the variant specification from the product.
     *
     * @param Variation $value The product variation.
     * @param object|null $object The object to extract the value from.
     * @return array
     */
    public function extract($value, ?object $object = null)
    {
        return $value->getVariantSpecification();
    }

    /**
     * Hydrate the variant specification of the product.
     *
     * @param Variation $value The product variation.
     * @param array|null $data The data to hydrate the value with.
     * @return Variation
     */
    public function hydrate($value, ?array $data)
    {
        $attrValues = [];
        foreach ($this->attributeCollection->getItems() as $attribute) {
            $attr = $this->objectManager->create(
                Attribute::class,
                [
                    'eavAttribute' => $attribute,
                ]
            );
            if (! $attr->isVariant()) {
                continue;
            }
            $attrValue = $this->objectManager->create(
                Value::class
            );
            $values    = $this->getAttributeValueLabel(
                $this->product,
                $attribute->getAttributeCode()
            );

            if (! $values || empty($values)) {
                continue;
            }

            $attrValue->setAttribute(
                $attribute->getAttributeCode()
            )->setValues(
                is_array($values) ? $values : [(string) $values]
            );

            $attrValues[] = $attrValue;
        }

        $value->setVariantSpecification($attrValues);
        return $value;
    }
}
