<?php

declare(strict_types=1);

namespace Gubee\Integration\Controller\Adminhtml\Invoice;

use Exception;
use Gubee\Integration\Controller\Adminhtml\AbstractInvoice;
use Gubee\Integration\Model\Invoice;
use Magento\Framework\Controller\ResultInterface;

use function __;

class Delete extends AbstractInvoice
{
    /**
     * Delete action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $id             = $this->getRequest()->getParam('invoice_id');
        if ($id) {
            try {
                $model = $this->_objectManager->create(Invoice::class);
                $model->load($id);
                $model->delete();
                $this->messageManager->addSuccessMessage(
                    __('You deleted the Invoice.')
                );
                return $resultRedirect->setPath('*/*/');
            } catch (Exception $e) {
                $this->messageManager->addErrorMessage(
                    $e->getMessage()
                );
                return $resultRedirect->setPath(
                    '*/*/edit',
                    [
                        'invoice_id' => $id,
                    ]
                );
            }
        }
        $this->messageManager->addErrorMessage(
            __('We can\'t find a Invoice to delete.')
        );
        return $resultRedirect->setPath('*/*/');
    }
}
