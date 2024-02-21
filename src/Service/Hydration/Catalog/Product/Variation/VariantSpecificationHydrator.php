<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product\Variation;

use Gubee\Integration\Helper\Config;
use Gubee\Integration\Model\ResourceModel\Catalog\Attribute\CollectionFactory;
use Gubee\SDK\Model\Catalog\Product\Attribute\Value;
use Magento\Eav\Model\AttributeSearchResults;
use Magento\Framework\ObjectManagerInterface;

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
        $values = [];
        foreach ($this->attributeCollection as $attribute) {
            $value = $this->objectManager->create(
                Value::class
            );
            $value->setAttribute(
                $attribute->getAttributeCode()
            )->setValues(
                [
                    $this->getRawAttributeValue(
                        $this->product,
                        $attribute->getAttributeCode()
                    ),
                ]
            );
        }

        $value->setVariantSpecification($values);
        return $value;
    }
}
