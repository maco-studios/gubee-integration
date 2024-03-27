<?php

declare(strict_types=1);

namespace Gubee\Integration\Model\Source\System\Config\General;

use Gubee\SDK\Resource\PlatformResource;
use Magento\Framework\Option\ArrayInterface;

class Fullfilment implements ArrayInterface
{
    protected PlatformResource $platformResource;
    public function __construct(
        PlatformResource $platformResource
    ) {
        $this->platformResource = $platformResource;
    }

    public function toOptionArray()
    {
        $options   = [];
        $platforms = $this->platformResource->configuration();
        foreach ($platforms as $platform) {
            $options[] = [
                'value' => $platform['code'],
                'label' => $platform['label'],
            ];
        }
        return $options;
    }
}