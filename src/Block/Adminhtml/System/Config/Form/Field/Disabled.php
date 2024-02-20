<?php

declare(strict_types=1);

namespace Gubee\Integration\Block\Adminhtml\System\Config\Form\Field;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

use function array_merge;

class Disabled extends Field
{
    /**
     * Creates a disabled element input field
     *
     * @return string
     */
    //phpcs:disable
    protected function _getElementHtml(AbstractElement $element)
    {
        $data = $element->getData();
        $data = array_merge(
            $data ?: [],
            [
                'readonly' => 1,
                'disabled' => 'disabled',
            ]
        );

        $element->setData($data);
        return parent::_getElementHtml($element);
    }
    //phpcs:enable
}
