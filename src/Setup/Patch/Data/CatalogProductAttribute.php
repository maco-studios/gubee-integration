<?php

declare(strict_types=1);

namespace Gubee\Integration\Setup\Patch\Data;

use Gubee\Integration\Model\Catalog\Product\Attribute\Source\HandlingTime;
use Gubee\Integration\Model\Catalog\Product\Attribute\Source\Origin;
use Gubee\Integration\Setup\AbstractMigration;
use Gubee\Integration\Setup\Migration\Context;
use Gubee\Integration\Setup\Migration\Facade\ProductAttribute;
use Magento\Eav\Model\Entity\Attribute\Source\Boolean;
use Magento\Eav\Model\Entity\Attribute\Source\Table;

use function array_merge;
use function sprintf;

class CatalogProductAttribute extends AbstractMigration
{
    protected ProductAttribute $productAttribute;
    /** @var array<string,array<string,mixed>> */
    protected array $attributes = [
        'gubee'                         => [
            'type'                    => 'int',
            'label'                   => 'Send product to Gubee',
            'user_defined'            => false,
            'is_visible'              => 0,
            'input'                   => 'boolean',
            'source'                  => Boolean::class,
            'used_in_product_listing' => true,
        ],
        'gubee_sync'                    => [
            'type'    => 'int',
            'input'   => 'boolean',
            'label'   => 'Keep synced with Gubee',
            'source'  => Boolean::class,
            'comment' => 'If checked, this product will automatically be synced with Gubee on every save.',
        ],
        'gubee_brand'                   => [
            'type'   => 'int',
            'label'  => 'Brand',
            'input'  => 'select',
            'source' => Table::class,
        ],
        'gubee_origin'                  => [
            'type'   => 'varchar',
            'label'  => 'Origin',
            'input'  => 'select',
            'source' => Origin::class,
        ],
        'gubee_nbm'                     => [
            'type'  => 'varchar',
            'label' => 'NBM',
        ],
        'gubee_ean'                     => [
            'type'  => 'varchar',
            'label' => 'EAN',
        ],
        'gubee_width'                   => [
            'type'  => 'decimal',
            'label' => 'Width',
        ],
        'gubee_height'                  => [
            'type'  => 'decimal',
            'label' => 'Height',
        ],
        'gubee_depth'                   => [
            'type'  => 'decimal',
            'label' => 'Depth',
        ],
        'gubee_handling_time'           => [
            'type'  => 'int',
            'label' => 'Handling Time',
        ],
        'gubee_handling_time_unit'      => [
            'type'   => 'varchar',
            'label'  => 'Handling Time Unit',
            'input'  => 'select',
            'source' => HandlingTime::class,
        ],
        'gubee_warranty_time'           => [
            'type'  => 'int',
            'label' => 'Warranty Time',
        ],
        'gubee_warranty_time_unit'      => [
            'type'   => 'varchar',
            'label'  => 'Warranty Time Unit',
            'input'  => 'select',
            'source' => HandlingTime::class,
        ],
        'gubee_price'                   => [
            'type'  => 'decimal',
            'label' => 'Price',
            'input' => 'price',
        ],
        'gubee_cross_docking_time'      => [
            'type'  => 'int',
            'label' => 'Cross Docking Time',
        ],
        'gubee_cross_docking_time_unit' => [
            'type'    => 'varchar',
            'label'   => 'Cross Docking Time Unit',
            'input'   => 'select',
            'source'  => HandlingTime::class,
            'default' => 'DAYS',
            'note'    => 'Time to prepare the product to be shipped after the'
            . ' order is placed, if none is set days will be used by default.',
        ],
    ];

    public function __construct(
        ProductAttribute $productAttribute,
        Context $context
    ) {
        $this->productAttribute = $productAttribute;
        parent::__construct($context);
    }

    /**
     * {@inheritdoc}
     */
    public function execute(): void
    {
        foreach ($this->attributes as $attributeCode => $attrValue) {
            $attrValue = array_merge(
                [
                    'label'        => $this->getAttributeLabel($attrValue['label']),
                    'user_defined' => true,
                    'required'     => false,
                ],
                $attrValue
            );
            if ($this->productAttribute->exists($attributeCode)) {
                $this->productAttribute->update(
                    $attributeCode,
                    $attrValue
                );
            } else {
                $this->productAttribute->create(
                    $attributeCode,
                    $attrValue
                );
            }
        }
    }

    public function getAttributeLabel(string $label): string
    {
        if ($label === 'gubee') {
            return 'Gubee';
        }
        return sprintf(
            "Gubee > %s",
            $label
        );
    }
}
