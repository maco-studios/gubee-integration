<?php

declare(strict_types=1);

namespace Gubee\Integration\Observer\Catalog\Product\Stock\Item\Save;

use Gubee\Integration\Command\Catalog\Product\Stock\SendCommand;
use Gubee\Integration\Observer\Catalog\Product\AbstractProduct;
use Magento\Catalog\Api\Data\ProductInterface;
use Magento\Framework\Event\Observer;

use function json_decode;
use function json_encode;

class After extends AbstractProduct
{
    protected function process(): void
    {
        $this->queueManagement->append(
            SendCommand::class,
            [
                'sku' => $this->getProduct()->getSku(),
            ]
        );
        $this->appendForParent(
            $this->getProduct()
        );
    }

    /**
     * Execute the observer
     *
     * @return void
     */
    public function execute(Observer $observer)
    {
        if (! $observer->getDataObject() instanceof ProductInterface) {
            $product = $this->productRepository->getById(
                $observer->getDataObject()->getProductId()
            );
        } else {
            $product = $observer->getDataObject();
        }
        $this->setProduct($product);
        if ($this->isAllowed()) {
            $this->process();
            $this->appendForParent(
                $this->getProduct()
            );
        }
    }

    /**
     * Validate if the observer is allowed to run
     */
    protected function isAllowed(): bool
    {
        $product  = $this->getProduct();
        $origJson = json_decode(
            json_encode(
                $product->getOrigData()
            ),
            true
        );
        if (isset($origJson['quantity_and_stock_status'])) {
            if (
                $origJson['quantity_and_stock_status']['qty']
                ==
                $product->getData('stock_data/qty')
                &&
                $origJson['quantity_and_stock_status']['is_in_stock']
                ==
                $product->getData('stock_data/is_in_stock')
            ) {
                return false;
            }
        }

        return parent::isAllowed();
    }
}
