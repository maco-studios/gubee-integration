<?php

declare (strict_types = 1);

namespace Gubee\Integration\Ui\Component\Sales\Order\Listing\Column;

use Gubee\SDK\Resource\PlatformResource;
use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;

class Marketplace extends \Magento\Ui\Component\Listing\Columns\Column {

    protected static $platform = [];
    protected PlatformResource $platformResource;

    public function __construct(
        PlatformResource $platformResource,
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        array $components = [],
        array $data = []
    ) {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->platformResource = $platformResource;
    }

    /**
     * Prepare status column
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource) {
        if (isset($dataSource['data']['items'])) {
            foreach ($dataSource['data']['items'] as &$item) {
                $status = $item['status'];
                $item[$this->getData('name')] = $this->getMarketplaceCell($item[$this->getData('name')]);
            }
        }

        return $dataSource;
    }

    public function getMarketplaceCell(string $marketplace) {
        $content = "";
        if ($this->getMarketplaceLogo($marketplace)) {
            $content .= '<img src="' . $this->getMarketplaceLogo($marketplace) . '" alt="' . $marketplace . '" width="40" />';
        }
        $content .= '<span style="padding:5px;font-weight:bold">' . $marketplace . '</span>';

        return <<<HTML
            <div class="gubee-marketplace-logo" style="display: flex; align-items: center;">
                $content
            </div>
        HTML;
    }

    /**
     * Get marketplace logo
     *
     * @param string $marketplace
     * @return string|null
     */
    private function getMarketplaceLogo(string $marketplace): ?string {
        try {
            $platformConfig = $this->getPlatformConfig();
        } catch (\Exception $e) {
            return null;
        }

        $logo = isset($platformConfig[$marketplace]['logoUrl'])
        ? $platformConfig[$marketplace]['logoUrl']
        : $platformConfig['HUBEE']['logoUrl'];
        return $this->isAvailable(
            $logo
        ) ? $logo : $platformConfig['HUBEE']['logoUrl'];
    }

    public function isAvailable(string $logoUrl): bool {
        $curl = curl_init($logoUrl);
        curl_setopt($curl, CURLOPT_NOBODY, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        curl_exec($curl);
        // check if content is a file
        if (curl_getinfo($curl, CURLINFO_CONTENT_TYPE) == 'image/jpeg') {
            return true;
        }
        $httpcode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        return $httpcode == 200;
    }

    public function getPlatformConfig() {
        if (empty(self::$platform)) {
            $result = $this->platformResource->configuration();
            foreach ($result as $key => $value) {
                $result[$value['code']] = $value;
                unset($result[$key]);
            }

            self::$platform = $result;
        }

        return self::$platform;
    }
}
