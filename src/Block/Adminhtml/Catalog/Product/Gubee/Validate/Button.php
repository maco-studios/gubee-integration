<?php

declare(strict_types=1);

namespace Gubee\Integration\Block\Adminhtml\Catalog\Product\Gubee\Validate;

use Gubee\Integration\Service\Hydration\Catalog\Eav\Attribute\AttributeTrait;
use Gubee\Integration\Service\Model\Catalog\Product;
use Gubee\SDK\Resource\Catalog\Product\ValidateResource;
use Magento\Backend\Block\Widget\Container;
use Magento\Backend\Block\Widget\Context;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\App\ObjectManager;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Registry;
use Magento\Store\Model\App\Emulation;
use Throwable;

class Button extends Container
{
    use AttributeTrait;

    protected ProductInterface $product;
    protected Registry $coreRegistry;
    protected Emulation $emulation;
    protected ObjectManagerInterface $objectManager;
    protected ValidateResource $validateResource;

    /**
     * @param array                                 $data
     */
    public function __construct(
        Context $context,
        Registry $registry,
        ValidateResource $validateResource,
        ProductInterface $product,
        Emulation $emulation,
        ObjectManagerInterface $objectManager,
        array $data = []
    ) {
        $this->validateResource = $validateResource;
        //phpcs:disable
        $this->_request      = $context->getRequest();
        //phpcs:enable
        $this->objectManager = $objectManager;
        $this->coreRegistry = $registry;
        $this->product      = $product;
        $this->emulation    = $emulation;
        parent::__construct($context, $data);
    }


    /**
     * Block constructor adds buttons
     */
    protected function _construct()
    {
        $this->addButton(
            'gubee_validation',
            $this->getButtonData()
        );
        parent::_construct();
    }

    /**
     * Only output if product has gubee flagged
     */
    protected function _toHtml()
    {
        $product = $this->coreRegistry->registry('current_product');
        return parent::_toHtml();
    }

    /**
     * Return button attributes array
     */
    public function getButtonData()
    {
        $problems = $this->validate() ?: [];
        return [
            'label'      => json_encode(
                $problems,
                JSON_PRETTY_PRINT
                    | JSON_NUMERIC_CHECK
                    | JSON_UNESCAPED_SLASHES
                    | JSON_UNESCAPED_UNICODE
            ),
            'sort_order' => 800,
            'class' => 'relative action-secondary'
        ];
    }

    /**
     * Return product frontend url depends on active store
     *
     * @return mixed
     */
    protected function _getProductUrl()
    {
        return null;
    }

    /**
     * @return mixed
     */
    public function validate()
    {
        $problems = [];
        try {
            $product = $this->objectManager->create(
                Product::class,
                [
                    'product' => $this->coreRegistry->registry('current_product'),
                ]
            );
            $errors = $this->validateResource->create(
                $product
            );
            foreach ($errors as $error) {
                $problems[] = sprintf(
                    "%s.%s: %s",
                    $error['objectName'],
                    $error['field'],
                    $error['message']
                );
            }
        } catch (Throwable $e) {
            throw $e;
        }

        return $problems ?: [];
    }
}
