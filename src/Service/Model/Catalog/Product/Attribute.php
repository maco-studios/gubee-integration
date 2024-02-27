<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Model\Catalog\Product;

use Exception;
use Gubee\SDK\Api\Catalog\Product\AttributeApi;
use Gubee\SDK\Library\HttpClient\Exception\NotFoundException;
use Gubee\SDK\Model\Catalog\Product\Attribute as ProductAttribute;
use Laminas\Hydrator\Strategy\StrategyChain;
use Magento\Catalog\Api\Data\EavAttributeInterface;
use Magento\Framework\ObjectManagerInterface;

class Attribute extends ProductAttribute
{
    protected EavAttributeInterface $eavAttribute;
    protected AttributeApi $attributeApi;
    protected StrategyChain $hydrator;

    public function __construct(
        EavAttributeInterface $eavAttribute,
        AttributeApi $attributeApi,
        ObjectManagerInterface $objectManager,
        iterable $strategies = []
    ) {
        $this->eavAttribute = $eavAttribute;
        $this->attributeApi = $attributeApi;

        foreach ($strategies as $strategy) {
            $strategy->setEavAttribute($eavAttribute);
        }

        $this->hydrator = $objectManager->create(
            StrategyChain::class,
            [
                'extractionStrategies' => $strategies,
            ]
        );
        $this->hydrator->hydrate($this);
    }

    public function load(string $id, string $field = 'external_id')
    {
        try {
            switch ($field) {
                case 'external_id':
                    $attribute = $this->attributeApi->loadByExternalId(
                        $id
                    );
                    break;
                case 'name':
                    $attribute = $this->attributeApi->loadByName(
                        $id
                    );
                    break;
                default:
                    throw new Exception('Invalid field');
            }
            $this->attributeApi->getHydrator()
                ->hydrate($attribute, $this);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function save(): self
    {
        try {
            $this->attributeApi->loadByName($this->getName());
            $response = $this->attributeApi->updateByName(
                $this->getName(),
                $this
            );
            $this->attributeApi->getHydrator()
                ->hydrate($response, $this);
            return $this;
        } catch (NotFoundException $e) {
        } catch (Exception $e) {
            throw $e;
        }
        try {
            if ($this->getId()) {
                $this->attributeApi->loadByExternalId($this->getId());
                $response = $this->attributeApi->update(
                    $this->getId(),
                    $this
                );
            } else {
                $response = $this->attributeApi->create($this);
            }
            $this->attributeApi->getHydrator()
                ->hydrate($response, $this);
        } catch (NotFoundException $e) {
        } catch (Exception $e) {
            throw $e;
        }

        return $this;
    }
}
