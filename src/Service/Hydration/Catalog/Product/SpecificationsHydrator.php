<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product;

use Gubee\Integration\Helper\Config;
use Gubee\Integration\Model\ResourceModel\Catalog\Attribute\CollectionFactory;
use Gubee\Integration\Service\Hydration\Catalog\Eav\Attribute\AttributeTrait;
use Gubee\SDK\Model\Catalog\Product\Attribute\Value;
use Magento\Eav\Api\Data\AttributeSearchResultsInterface;
use Magento\Framework\ObjectManagerInterface;

use function is_array;

class SpecificationsHydrator extends AbstractHydrator
{
    use AttributeTrait;

    protected AttributeSearchResultsInterface $collection;
    protected ObjectManagerInterface $objectManager;

    public function __construct(
        CollectionFactory $collectionFactory,
        ObjectManagerInterface $objectManager,
        Config $config
    ) {
        $this->objectManager = $objectManager;
        $this->collection    = $collectionFactory->create();
        parent::__construct($config);
    }

    /**
     * Extract the variant attributes from the product.
     *
     * @param mixed $value
     * @return mixed
     */
    public function extract($value, ?object $object = null)
    {
        return $value->getSpecifications();
    }

    /**
     * Hydrate the product variant attributes.
     *
     * @param mixed $value
     * @param array|null $data
     * @return mixed
     */
    public function hydrate($value, ?array $data)
    {
        $variantAttributes = [];

        foreach ($this->collection->getItems() as $attribute) {
            $attrValue = $this->objectManager->create(
                Value::class
            );
            $values    = $this->getRawAttributeValue(
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
            $variantAttributes[] = $attrValue;
        }

        $value->setSpecifications($variantAttributes);

        return $value;
    }
}
