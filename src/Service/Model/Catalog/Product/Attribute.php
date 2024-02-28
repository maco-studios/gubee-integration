<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Model\Catalog\Product;

use Exception;
use Gubee\SDK\Library\HttpClient\Exception\NotFoundException;
use Gubee\SDK\Model\Catalog\Product\Attribute as ProductAttribute;
use Gubee\SDK\Resource\Catalog\Product\AttributeResource;
use Laminas\Hydrator\Strategy\StrategyChain;
use Magento\Catalog\Api\Data\EavAttributeInterface;
use Magento\Framework\ObjectManagerInterface;

class Attribute extends ProductAttribute
{
    protected EavAttributeInterface $eavAttribute;
    protected AttributeResource $attributeResource;

    public function __construct(
        EavAttributeInterface $eavAttribute,
        AttributeResource $attributeResource,
        ObjectManagerInterface $objectManager,
        iterable $strategies = []
    ) {
        $this->eavAttribute      = $eavAttribute;
        $this->attributeResource = $attributeResource;
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
                    $attribute = $this->attributeResource->loadByExternalId(
                        $id
                    );
                    break;
                case 'name':
                    $attribute = $this->attributeResource->loadByName(
                        $id
                    );
                    break;
                default:
                    throw new Exception('Invalid field');
            }
            $this->attributeResource->getHydrator()
                ->hydrate($attribute, $this);
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    public function save(): self
    {
        try {
            $this->attributeResource->loadByName($this->getName());
            $response = $this->attributeResource->updateByName(
                $this->getName(),
                $this
            );
            $this->attributeResource->getHydrator()
                ->hydrate($response, $this);
            return $this;
        } catch (NotFoundException $e) {
        } catch (Exception $e) {
            throw $e;
        }
        try {
            if ($this->getId()) {
                $this->attributeResource->loadByExternalId($this->getId());
                $response = $this->attributeResource->update(
                    $this->getId(),
                    $this
                );
            } else {
                $response = $this->attributeResource->create($this);
            }
            $this->attributeResource->getHydrator()
                ->hydrate($response, $this);
        } catch (NotFoundException $e) {
        } catch (Exception $e) {
            throw $e;
        }

        return $this;
    }
}
