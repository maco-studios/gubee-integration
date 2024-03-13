<?php

declare(strict_types=1);

namespace Gubee\Integration\Service\Model\Catalog\Product;

use Gubee\SDK\Enum\Catalog\Product\Attribute\TypeEnum;
use Gubee\SDK\Model\Catalog\Product\Attribute as ProductAttribute;
use Gubee\SDK\Resource\Catalog\Product\AttributeResource;
use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\ConfigurableProduct\Model\Product\Type\Configurable;
use Magento\Framework\App\ObjectManager;

use function in_array;
use function trim;

class Attribute extends ProductAttribute
{
    protected AttributeResource $attributeResource;

    /**
     * @param array<string>|null $options
     */
    public function __construct(
        AttributeResource $attributeResource,
        string $name,
        ?TypeEnum $attrType = null,
        ?string $hubeeId = null,
        ?string $id = null,
        ?string $label = null,
        ?array $options = null,
        ?bool $required = null,
        ?bool $variant = null
    ) {
        $this->attributeResource = $attributeResource;
        parent::__construct(
            $name,
            $attrType,
            $hubeeId,
            $id,
            $label,
            $options,
            $required,
            $variant
        );
    }

    public static function fromEavAttribute(
        ProductAttributeInterface $attribute
    ): self {
        $objManager = ObjectManager::getInstance();
        $instance   = $objManager->create(
            self::class,
            [
                'name'     => $attribute->getAttributeCode(),
                'id'       => $attribute->getAttributeCode(),
                'label'    => $attribute->getDefaultFrontendLabel(),
                'required' => $attribute->getIsRequired() ?: false,
            ]
        );
        switch ($attribute->getFrontendInput()) {
            case 'textarea':
                $instance->setAttrType(TypeEnum::TEXTAREA());
                break;
            case 'multiselect':
                $instance->setAttrType(TypeEnum::MULTISELECT());
                break;
            case 'select':
                $instance->setAttrType(TypeEnum::SELECT());
                break;
            case 'text':
            case 'price':
            default:
                $instance->setAttrType(TypeEnum::TEXT());
                break;
        }

        $isVariant = false;
        if (
            $attribute->getIsGlobal() === "1" /** @phpstan-ignore-line */
            && $attribute->getIsUserDefined() === true
            && $attribute->getFrontendInput() === "select"
        ) {
            if (
                ! $attribute->getApplyTo()
                ||
                in_array(
                    Configurable::TYPE_CODE,
                    $attribute->getApplyTo()
                )
            ) {
                $isVariant = true;
            }
        }
        $instance->setVariant($isVariant);
        $options = [];
        foreach ($attribute->getOptions() ?: [] as $option) {
            $label = (string) $option->getLabel();
            $label = trim($label);
            if ($label === '') {
                continue;
            }
            $options[] = $label;
        }

        $instance->setOptions($options);

        return $instance;
    }
}
