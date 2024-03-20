<?php

declare(strict_types=1);

namespace Gubee\Integration\Block\Adminhtml\Invoice\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

use function __;

class SaveButton extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label'          => __('Save Invoice'),
            'class'          => 'save primary',
            'data_attribute' => [
                'mage-init' => ['button' => ['event' => 'save']],
                'form-role' => 'save',
            ],
            'sort_order'     => 90,
        ];
    }
}
