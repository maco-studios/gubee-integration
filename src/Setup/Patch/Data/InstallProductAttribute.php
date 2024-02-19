<?php

declare(strict_types=1);

namespace Gubee\Integration\Setup\Patch\Data;

use Exception;
use Gubee\Integration\Library\Setup\AbstractMigration;
use Gubee\Integration\Library\Setup\Migration\Context;
use Gubee\Integration\Library\Setup\Migration\Facade\Catalog\Product\Attribute;
use Gubee\Integration\Model\Catalog\Product\Attribute\Source\HandlingTime;
use Gubee\Integration\Model\Catalog\Product\Attribute\Source\Origin;
use Gubee\SDK\Interfaces\Catalog\Product\Attribute\Dimension\UnitTimeInterface;
use Magento\Eav\Model\Entity\Attribute\Source\Boolean;

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
        ],
        'gubee_sync'                    => [
            'type'    => 'int',
            'input'   => 'boolean',
            'label'   => 'Gubee > Keep synced with Gubee',
            'source'  => Boolean::class,
            'comment' => 'If checked, this product will automatically be synced with Gubee on every save.',
        ],
        'gubee_brand'                   => [
            'type'   => 'int',
            'label'  => 'Gubee > Brand',
            'input'  => 'select',
            'source' => Table::class,
        ],
        'gubee_origin'                  => [
            'type'   => 'varchar',
            'label'  => 'Gubee > Origin',
            'input'  => 'select',
            'source' => Origin::class,
        ],
        'gubee_nbm'                     => [
            'type'  => 'varchar',
            'label' => 'Gubee > NBM',
        ],
        'gubee_ean'                     => [
            'type'  => 'varchar',
            'label' => 'Gubee > EAN',
        ],
        'gubee_width'                   => [
            'type'  => 'decimal',
            'label' => 'Gubee > Width',
        ],
        'gubee_height'                  => [
            'type'  => 'decimal',
            'label' => 'Gubee > Height',
        ],
        'gubee_depth'                   => [
            'type'  => 'decimal',
            'label' => 'Gubee > Depth',
        ],
        'gubee_handling_time'           => [
            'type'  => 'int',
            'label' => 'Gubee > Handling Time',
        ],
        'gubee_handling_time_unit'      => [
            'type'   => 'varchar',
            'label'  => 'Gubee > Handling Time Unit',
            'input'  => 'select',
            'source' => HandlingTime::class,
        ],
        'gubee_warranty_time'           => [
            'type'  => 'int',
            'label' => 'Gubee > Warranty Time',
        ],
        'gubee_warranty_time_unit'      => [
            'type'   => 'varchar',
            'label'  => 'Gubee > Warranty Time Unit',
            'input'  => 'select',
            'source' => HandlingTime::class,
        ],
        'gubee_price'                   => [
            'type'  => 'decimal',
            'label' => 'Gubee > Price',
            'input' => 'price',
        ],
        'gubee_cross_docking_time'      => [
            'type'  => 'int',
            'label' => 'Gubee > Cross Docking Time',
        ],
        'gubee_cross_docking_time_unit' => [
            'type'    => 'varchar',
            'label'   => 'Gubee > Cross Docking Time Unit',
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
