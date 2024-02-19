<?php

declare(strict_types=1);

namespace Gubee\Integration\Ui\Component\Queue\Listing\Column;

use Gubee\Integration\Api\Data\QueueInterface;
use Magento\Ui\Component\Listing\Columns\Column;

use function __;

class Actions extends Column
{
    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $item[$this->getData('name')] = [
                    'execute' => [
                        'href'  => $this->prepareExecuteUrl($item[QueueInterface::QUEUE_ID]),
                        'label' => __('Execute'),
                    ],
                ];
            }
        }

        return $dataSource;
    }

    public function prepareExecuteUrl(string $id): string
    {
        return $this->context->getUrl(
            'gubee_integration/queue/execute',
            [
                'id' => $id,
            ]
        );
    }
}
