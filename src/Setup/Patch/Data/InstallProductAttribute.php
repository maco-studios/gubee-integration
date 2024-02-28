<?php

declare(strict_types=1);

namespace Gubee\Integration\Setup\Patch\Data;

use Exception;
use Gubee\Integration\Library\Setup\AbstractMigration;
use Gubee\Integration\Library\Setup\Migration\Context;
use Gubee\Integration\Library\Setup\Migration\Facade\Catalog\Product\Attribute;
use Gubee\Integration\Model\Catalog\Product\Attribute\Source\HandlingTime;
use Gubee\Integration\Model\Catalog\Product\Attribute\Source\Origin;
use Gubee\SDK\Api\Catalog\Product\Attribute\Dimension\UnitTimeInterface;
use Magento\Eav\Model\Entity\Attribute\Source\Boolean;
use Magento\Eav\Model\Entity\Attribute\Source\Table;

use function sprintf;

class InstallProductAttribute extends AbstractMigration
{
    /** @var array */
    protected $attributes = [
        'gubee'                         => [
            'type'                    => 'int',
            'label'                   => 'Gubee > Send product to Gubee',
            'user_defined'            => false,
            'is_visible'              => 0,
            'input'                   => 'boolean',
            'source'                  => Boolean::class,
            'used_in_product_listing' => true,
            'group'                   => 'Gubee',
        ],
        'gubee_sync'                    => [
            'type'    => 'int',
            'input'   => 'boolean',
            'label'   => 'Gubee > Keep synced with Gubee',
            'source'  => Boolean::class,
            'comment' => 'If checked, this product will automatically be synced with Gubee on every save.',
            'group'   => 'Gubee',
        ],
        'gubee_brand'                   => [
            'type'   => 'int',
            'label'  => 'Gubee > Brand',
            'input'  => 'select',
            'source' => Table::class,
            'group'  => 'Gubee',
        ],
        'gubee_origin'                  => [
            'type'   => 'varchar',
            'label'  => 'Gubee > Origin',
            'input'  => 'select',
            'source' => Origin::class,
            'group'  => 'Gubee',
        ],
        'gubee_nbm'                     => [
            'type'  => 'varchar',
            'label' => 'Gubee > NBM',
            'group' => 'Gubee',
        ],
        'gubee_ean'                     => [
            'type'  => 'varchar',
            'label' => 'Gubee > EAN',
            'group' => 'Gubee',
        ],
        'gubee_width'                   => [
            'type'  => 'decimal',
            'label' => 'Gubee > Width',
            'group' => 'Gubee',
        ],
        'gubee_height'                  => [
            'type'  => 'decimal',
            'label' => 'Gubee > Height',
            'group' => 'Gubee',
        ],
        'gubee_depth'                   => [
            'type'  => 'decimal',
            'label' => 'Gubee > Depth',
            'group' => 'Gubee',
        ],
        'gubee_handling_time'           => [
            'type'  => 'int',
            'label' => 'Gubee > Handling Time',
            'group' => 'Gubee',
        ],
        'gubee_handling_time_unit'      => [
            'type'   => 'varchar',
            'label'  => 'Gubee > Handling Time Unit',
            'input'  => 'select',
            'source' => HandlingTime::class,
            'group'  => 'Gubee',
        ],
        'gubee_warranty_time'           => [
            'type'  => 'int',
            'label' => 'Gubee > Warranty Time',
            'group' => 'Gubee',
        ],
        'gubee_warranty_time_unit'      => [
            'type'   => 'varchar',
            'label'  => 'Gubee > Warranty Time Unit',
            'input'  => 'select',
            'source' => HandlingTime::class,
            'group'  => 'Gubee',
        ],
        'gubee_price'                   => [
            'type'  => 'decimal',
            'label' => 'Gubee > Price',
            'input' => 'price',
            'group' => 'Gubee',
        ],
        'gubee_cross_docking_time'      => [
            'type'  => 'int',
            'label' => 'Gubee > Cross Docking Time',
            'group' => 'Gubee',
        ],
        'gubee_cross_docking_time_unit' => [
            'type'    => 'varchar',
            'label'   => 'Gubee > Cross Docking Time Unit',
            'group'   => 'Gubee',
            'input'   => 'select',
            'source'  => HandlingTime::class,
            'default' => UnitTimeInterface::DAYS,
            'note'    => 'Time to prepare the product to be shipped after the '
            . 'order is placed, if none is set days will be used by default.',
        ],
    ];

    protected Attribute $attribute;

    public function __construct(
        Context $context,
        Attribute $attribute
    ) {
        parent::__construct($context);
        $this->attribute = $attribute;
    }

    protected function execute(): void
    {
        foreach ($this->attributes as $code => $data) {
            try {
                if (! $this->attribute->exists($code)) {
                    $this->getContext()->getLogger()
                        ->info(
                            sprintf(
                                "Creating attribute '%s'",
                                $code
                            )
                        );
                    $this->attribute->create($code, $data);
                    $this->getContext()->getLogger()
                        ->info(
                            sprintf(
                                "Attribute '%s' created successfully",
                                $code
                            )
                        );
                } else {
                    $this->attribute->update($code, $data);
                    $this->getContext()->getLogger()
                        ->info(
                            sprintf(
                                "Attribute '%s' updated successfully",
                                $code
                            )
                        );
                }
            } catch (Exception $e) {
                $this->getContext()->getLogger()->error($e->getMessage());
            }
        }
    }
}
