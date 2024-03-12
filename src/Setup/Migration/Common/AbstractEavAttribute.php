<?php

declare(strict_types=1);

namespace Gubee\Integration\Setup\Migration\Common;

use Magento\Eav\Model\Config;
use Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;

abstract class AbstractEavAttribute implements ScopedAttributeInterface
{
    public const ENTITY_TYPE = 'OVERRIDE THIS IS CHILD CLASSES';
    protected Config $config;
    protected EavSetupFactory $eavSetupFactory; /** @phpstan-ignore-line */

    protected ResourceConnection $resourceConnection;

    // phpcs:ignore
    public function __construct(
        EavAttribute\Context $context
    ) {
        $this->config             = $context->getConfig();
        $this->eavSetupFactory    = $context->getEavSetupFactory();
        $this->resourceConnection = $context->getResourceConnection();
    }

    /**
     * Create a new attribute
     *
     * @param string $code
     * @param array<string, mixed> $data
     * @return EavSetup
     */
    public function create($code, $data)
    {
        return $this->getEavSetup()->addAttribute(
            static::ENTITY_TYPE,
            $code,
            $data
        );
    }

    /**
     * Update an existing attribute
     *
     * @param string $code
     * @param array<string, mixed> $data
     * @return EavSetup
     */
    public function update($code, $data)
    {
        return $this->getEavSetup()->updateAttribute(
            static::ENTITY_TYPE,
            $code,
            $data
        );
    }

    /**
     * Check if given attribute exists
     *
     * @param string $code
     * @return bool
     */
    public function exists($code)
    {
        return (bool) $this->config->getAttribute(static::ENTITY_TYPE, $code)->getId();
    }

    /**
     * Retrieve a fresh instance of the EavSetup
     *
     * @return EavSetup
     */
    protected function getEavSetup()
    {
        return $this->eavSetupFactory->create(); /** @phpstan-ignore-line */
    }

    /**
     * Database facade for quick operations
     */
    protected function getConnection(): AdapterInterface
    {
        return $this->resourceConnection->getConnection();
    }

    /**
     * Retrieve given table name on database,
     * with preffix and etc if applicable
     *
     * @param string $rawTableName
     * @return string
     */
    protected function getTableName($rawTableName)
    {
        return $this->resourceConnection->getTableName($rawTableName);
    }

    /**
     * Retrieve entity type if
     *
     * @return int
     */
    protected function getEntityTypeId()
    {
        $tableName = $this->getTableName('eav_entity_type');
        $select    = $this->getConnection()->select()
            ->from($tableName, 'entity_type_id')
            ->where('entity_type_code=?', static::ENTITY_TYPE);

        return (int) $this->getConnection()->fetchOne($select);
    }
}
