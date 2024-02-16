<?php

declare(strict_types=1);

namespace Gubee\Integration\Setup\Patch\Data;

use Gubee\Integration\Library\Setup\AbstractMigration;
use Gubee\Integration\Library\Setup\Migration\Context;
use Gubee\Integration\Library\Setup\Migration\Facade\Catalog\Category\Attribute;

class InstallCategoryAttribute extends AbstractMigration
{
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
        $attribute = [
            'type'   => 'int',
            'label'  => 'Gubee > Active sync with Gubee',
            'source' => Boolean::class,
            'input'  => 'boolean',
        ];

        if (! $this->attribute->exists('gubee')) {
            $this->attribute->create('gubee', $attribute);
        } else {
            $this->attribute->update('gubee', $attribute);
        }
    }
}
