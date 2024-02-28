<?php

declare(strict_types=1);

namespace Gubee\Integration\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\Registry;

use function __;

abstract class AbstractInvoice extends Action
{
    //phpcs:disable
    protected $_coreRegistry;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Registry $coreRegistry
     */
    public function __construct(
        Context $context,
        Registry $coreRegistry
    ) {
        $this->_coreRegistry = $coreRegistry;
        parent::__construct($context);
    }
    //phpcs:enable

    /**
     * Init page
     *
     * @param Page $resultPage
     * @return Page
     */
    public function initPage($resultPage)
    {
        $resultPage->addBreadcrumb(
            __('Gubee'),
            __('Gubee')
        )->addBreadcrumb(
            __('Invoice'),
            __('Invoice')
        );
        return $resultPage;
    }
}
