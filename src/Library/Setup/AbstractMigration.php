<?php

declare(strict_types=1);

namespace Gubee\Integration\Library\Setup;

use Gubee\Integration\Library\Setup\Migration\Context;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Setup\Patch\PatchRevertableInterface;
use Throwable;

use function __;

abstract class AbstractMigration implements DataPatchInterface, PatchRevertableInterface
{
    protected const AREA_CODE = null;

    private Context $context;

    public function __construct(Context $context)
    {
        $this->context = $context;
    }

    /**
     * Your migration logic goes here
     */
    abstract protected function execute(): void;

    /**
     * Undo/revert the migration (optional)
     */
    // phpcs:disable
    protected function rollback(): void
    {
    }
    // phpcs:enable


    /**
     * @inheritDoc
     */
    final public function apply()
    {
        if ($appliedAlias = $this->hasAlreadyAppliedAlias()) {
            $this->getContext()->getLogger()->info(
                __(
                    'Patch data "%1" skipped because the it was already applied under the old name "%2"',
                    [static::class, $appliedAlias]
                )
            );

            return;
        }

        $this->getConnection()->startSetup();

        if ($areaCode = static::AREA_CODE) {
            try {
                $this->getContext()->getState()
                    ->setAreaCode($areaCode);
            } catch (Throwable $th) {
                $this->getContext()->getLogger()->error(
                    $th->getMessage()
                );
            }
        }

        $this->execute();

        $this->getConnection()->endSetup();
    }

    /**
     * @inheritDoc
     */
    final public function revert()
    {
        $this->getConnection()->startSetup();
        $this->rollback();
        $this->getConnection()->endSetup();
    }

    /**
     * Workaround for Magento core bug
     *
     * Return false if there's no previous applied alias,
     * otherwise return the applied alias name
     *
     * @see https://github.com/magento/magento2/issues/31396
     *
     * @return string|false
     */
    private function hasAlreadyAppliedAlias()
    {
        foreach ($this->getAliases() as $alias) {
            if ($this->context->getPatchHistory()->isApplied($alias)) {
                return $alias;
            }
        }

        return false;
    }

    /**
     * Shorthand for getting the database connection
     */
    protected function getConnection(): AdapterInterface
    {
        return $this->getModuleDataSetup()->getConnection();
    }

    /**
     * Get given table name in database including prefix
     */
    protected function getTableName(string $rawName): string
    {
        return $this->getModuleDataSetup()->getTable($rawName);
    }

    /**
     * Module Setup Data getter
     *
     * @return ModuleDataSetupInterface
     */
    protected function getModuleDataSetup()
    {
        return $this->context->getModuleDataSetup();
    }

    /**
     * @inheritDoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies()
    {
        return [];
    }

    public function getContext(): Context
    {
        return $this->context;
    }

    public function setContext(Context $context): self
    {
        $this->context = $context;
        return $this;
    }
}
