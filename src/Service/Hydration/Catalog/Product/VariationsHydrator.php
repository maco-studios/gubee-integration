<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product;

use Gubee\Integration\Helper\Config;
use Gubee\Integration\Service\Model\Catalog\Product\Variation;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\ObjectManagerInterface;

class VariationsHydrator extends AbstractHydrator
{
    protected ObjectManagerInterface $objectManager;

    public function __construct(
        Config $config,
        ObjectManagerInterface $objectManager
    ) {
        parent::__construct($config);
        $this->objectManager = $objectManager;
    }

    /**
     * Extracts the value from the object
     *
     * @param mixed $value
     * @return mixed
     */
    public function extract($value, ?object $object = null)
    {
        return $value->getVariations();
    }

    /**
     * Hydrates the value to the object
     *
     * @param mixed $value
     * @param array|null $data
     * @return mixed
     */
    public function hydrate($value, ?array $data)
    {
        $variations = [];
        if ($this->product->getTypeId() === Configurable::TYPE_CODE) {
            $variations = $this->getVariations();
        }
        $variation = $this->objectManager->create(
            Variation::class,
            [
                'product' => $this->product,
            ]
        );
        $variation->setIsMain(true);
        $variations[] = $variation;
        $value->setVariations($variations);
        return $value;
    }

    /**
     * Get the variations of the product
     *
     * @return array
     */
    protected function getVariations(): array
    {
        $variations = [];
        $children   = $this->product->getTypeInstance()
            ->getUsedProducts($this->product);
        foreach ($children as $child) {
            $variations[] = $this->objectManager->create(
                Variation::class,
                [
                    'product' => $child,
                ]
            );
        }
        return $variations;
    }
}
