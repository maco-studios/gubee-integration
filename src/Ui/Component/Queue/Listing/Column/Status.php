<?php

declare(strict_types=1);

namespace Gubee\Integration\Ui\Component\Queue\Listing\Column;

use Gubee\Integration\Api\Data\QueueInterface;
use Magento\Ui\Component\Listing\Columns\Column;

use function __;

class Status extends Column
{
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $status                       = $item['status'];
                $item[$this->getData('name')] = $this->getStatusHtml($status);
            }
        }

        return $dataSource;
    }

    public function getStatusHtml(string $status): string
    {
        switch ($status) {
            case QueueInterface::STATUS_PENDING:
                $class = 'grid-severity-minor';
                $label = __('Pending');
                break;
            case QueueInterface::STATUS_SUCCESS:
                $class = 'grid-severity-notice';
                $label = __('Success');
                break;
            case QueueInterface::STATUS_RUNNING:
                $class = 'grid-severity-minor';
                $label = __('Running');
                break;
            case QueueInterface::STATUS_STOPPED:
                $class = 'grid-severity-critical';
                $label = __('Stopped');
                break;
            case QueueInterface::STATUS_FAILED:
                $class = 'grid-severity-critical';
                $label = __('Failed');
                break;
            case QueueInterface::STATUS_ERROR:
                $class = 'grid-severity-critical';
                $label = __('Error');
                break;
        }

        return <<<HTML
<span class="{$class}"><span>{$label}</span></span>
HTML;
    }
}
