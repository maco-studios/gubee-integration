<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Hydration\Catalog\Product;

use Gubee\SDK\Model\Catalog\Product\Attribute as ProductAttribute;
use Magento\Catalog\Api\Data\EavAttributeInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;

use function in_array;
use function trim;

class Attribute
{
    protected EavAttributeInterface $eavAttribute;

    public function hydrate(ProductAttribute $attribute, EavAttributeInterface $eavAttribute): void
    {
        $this->eavAttribute = $eavAttribute;
        $attribute->setAttrType($this->getAttrType())
            ->setLabel($this->getLabel())
            ->setName($this->getName())
            ->setRequired($this->getRequired())
            ->setOptions($this->getOptions())
            ->setVariant($this->getVariant())
            ->setId($this->getId());
    }

    protected function getId(): string
    {
        return (string) $this->eavAttribute->getAttributeCode();
    }

    protected function getAttrType(): string
    {
        switch ($this->eavAttribute->getFrontendInput()) {
            case 'textarea':
                return ProductAttribute::TEXTAREA;
            case 'multiselect':
                return ProductAttribute::MULTISELECT;
            case 'select':
                return ProductAttribute::SELECT;
            case 'text':
            case 'price':
            default:
                return ProductAttribute::TEXT;
        }
    }

    protected function getLabel(): string
    {
        return $this->eavAttribute->getFrontendLabel();
    }

    protected function getName(): string
    {
        return $this->eavAttribute->getName();
    }

    protected function getRequired(): bool
    {
        return $this->eavAttribute->getIsRequired() ? true : false;
    }

    protected function getOptions(): array
    {
        $options = [];
        foreach ($this->eavAttribute->getOptions() ?: [] as $option) {
            $label = (string) $option->getLabel();
            $label = trim($label);
            if ($label === '') {
                continue;
            }
            $options[] = $label;
        }

        return $options;
    }

    protected function getVariant(): bool
    {
        if (
            $this->eavAttribute->getIsGlobal() === 1
            && $this->eavAttribute->getIsUserDefined() === 1
            && $this->eavAttribute->getFrontendInput() === "select"
        ) {
            if (
                ! $this->eavAttribute->getApplyTo()
                ||
                in_array(
                    Configurable::TYPE_CODE,
                    $this->eavAttribute->getApplyTo()
                )
            ) {
                return true;
            }
        }

        return false;
    }
}
