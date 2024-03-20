<?php

declare (strict_types = 1);

namespace Gubee\Integration\Ui\Component\Message\Listing\Column\Command\Filter;

use Magento\Framework\Option\ArrayInterface;

class Command implements ArrayInterface
{
    public function __construct(
        \Gubee\Integration\Model\ResourceModel\Message\CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    public function toOptionArray()
    {
        $result = [];
        foreach ($this->getOptions() as $value => $label) {
            $result[] = [
                'value' => $value,
                'label' => $label,
            ];
        }

        return $result;
    }

    public function getOptions()
    {
        $collection = $this->collectionFactory->create();
        $collection->addFieldToSelect('command');
        $collection->getSelect()->group('command');
        $options = [];
        foreach ($collection as $item) {
            $options[$item->getCommand()] = __($item->getCommand());
        }
        return $options;
    }
}
