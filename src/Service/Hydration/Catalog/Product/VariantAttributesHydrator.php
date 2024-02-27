<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product;

use Gubee\Integration\Helper\Config;
use Gubee\Integration\Model\ResourceModel\Catalog\Attribute\CollectionFactory;
use Gubee\Integration\Service\Hydration\Catalog\Eav\Attribute\AttributeTrait;
use Gubee\Integration\Service\Model\Catalog\Product\Attribute;
use Magento\Eav\Api\Data\AttributeSearchResultsInterface;
use Magento\Framework\ObjectManagerInterface;

use function in_array;

class VariantAttributesHydrator extends AbstractHydrator
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
        return $value->getVariantAttributes();
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
        $attributes        = $this->product->getTypeInstance()
            ->getConfigurableAttributes($this->product);
        $attrValueIds      = [];
        foreach ($attributes as $attribute) {
            $attrValueIds[] = $attribute->getAttributeId();
        }

        foreach ($this->collection->getItems() as $attribute) {
            if (! in_array($attribute->getAttributeId(), $attrValueIds)) {
                continue;
            }

            $attr = $this->objectManager->create(
                Attribute::class,
                [
                    'eavAttribute' => $attribute,
                ]
            );
            if (! $attr->isVariant()) {
                continue;
            }
            $variantAttributes[] = $attr->getId();
        }

        $value->setVariantAttributes($variantAttributes);
        return $value;
    }
}
