<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product;

use Exception;
use Gubee\Integration\Helper\Config;
use Gubee\Integration\Service\Model\Catalog\Product\Attribute\Brand;
use Gubee\SDK\Library\HttpClient\Exception\NotFoundException;

class BrandHydrator extends AbstractHydrator
{
    protected Brand $brand;
    protected Config $config;

    public function __construct(
        Config $config,
        Brand $brand
    ) {
        parent::__construct($config);
        $this->brand = $brand;
    }

    /**
     * Extracts the brand from the product
     *
     * @param ProductInterface $value
     * @return mixed
     */
    public function extract($value, ?object $object = null)
    {
        return $value->getBrand();
    }

    /**
     * Hydrates the brand into the product
     *
     * @param ProductInterface $value
     * @param array|null $data
     * @return mixed
     */
    public function hydrate($value, ?array $data)
    {
        $brand = $this->getAttributeValueLabel(
            $this->product,
            $this->config->getAttributeBrand()
        );
        if (! $brand) {
            return $value;
        }
        try {
            $this->brand->load($brand, 'name');
        } catch (NotFoundException $e) {
            $this->brand->setName($brand);
            $this->brand->save();
        } catch (Exception $e) {
            return $value;
        }

        try {
            $this->brand->load($brand, 'name');
        } catch (Exception $e) {
            throw $e;
        }

        $value->setBrand($this->brand);

        return $value;
    }
}
