<?php

declare(strict_types=1);

namespace Gubee\Integration\Ui\Component\Log\Listing\Column;

use Gubee\Integration\Api\Data\LogInterface;
use Magento\Ui\Component\Listing\Columns\Column;

use function __;

class Level extends Column
{
    /**
     * Prepare status column
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource): array
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $level                        = $item['level'];
                $item[$this->getData('name')] = $this->getLogLabel($level);
            }
        }

        return $dataSource;
    }

    /**
     * Get log label
     *
     * @param int $level
     */
    public function getLogLabel($level): string
    {
        switch ($level) {
            case LogInterface::EMERGENCY:
                $label = __('Emergency');
                $type  = 'emergency';
                break;
            case LogInterface::ALERT:
                $label = __('Alert');
                $type  = 'alert';
                break;
            case LogInterface::CRITICAL:
                $label = __('Critical');
                $type  = 'critical';
                break;
            case LogInterface::ERROR:
                $label = __('Error');
                $type  = 'error';
                break;
            case LogInterface::WARNING:
                $label = __('Warning');
                $type  = 'warning';
                break;
            case LogInterface::NOTICE:
                $label = __('Notice');
                $type  = 'notice';
                break;
            case LogInterface::INFO:
                $label = __('Info');
                $type  = 'info';
                break;
            case LogInterface::DEBUG:
                $label = __('Debug');
                $type  = 'debug';
                break;
        }

        return <<<HTML
            <span class="grid-log-{$type}"><span>{$label}</span></span>
        HTML;
    }
}
