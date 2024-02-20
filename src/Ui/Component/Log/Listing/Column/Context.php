<?php

declare(strict_types=1);

namespace Gubee\Integration\Ui\Component\Log\Listing\Column;

use Magento\Ui\Component\Listing\Columns\Column;

class Context extends Column
{
    /**
     * Prepare status column
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $item[$this->getData('name')] = <<<HTML
<textarea style="width: 100%;
height: 100px;
border:1px solid gray;
padding:5px;
background:rgba(0,0,0,.02)">
{$item[$this->getData('name')]}
</textarea>
HTML;
            }
        }

        return $dataSource;
    }
}
