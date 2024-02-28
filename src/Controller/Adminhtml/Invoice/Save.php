<?php

declare(strict_types=1);

namespace Gubee\Integration\Controller\Adminhtml\Invoice;

use Exception;
use Gubee\Integration\Model\Invoice;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;

use function __;

class Save extends Action
{
    protected DataPersistorInterface $dataPersistor;

    public function __construct(
        Context $context,
        DataPersistorInterface $dataPersistor
    ) {
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();
        $data           = $this->getRequest()->getPostValue();
        if ($data) {
            $id = $this->getRequest()->getParam('invoice_id');

            $model = $this->_objectManager->create(Invoice::class)
                ->load($id);
            if (! $model->getId() && $id) {
                $this->messageManager->addErrorMessage(
                    __('This Invoice no longer exists.')
                );
                return $resultRedirect->setPath('*/*/');
            }

            $model->setData($data);

            try {
                $model->save();
                $this->messageManager->addSuccessMessage(
                    __('You saved the Invoice.')
                );
                $this->dataPersistor->clear('gubee_integration_invoice');

                if ($this->getRequest()->getParam('back')) {
                    return $resultRedirect->setPath(
                        '*/*/edit',
                        [
                            'invoice_id' => $model->getId(),
                        ]
                    );
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage(
                    $e->getMessage()
                );
            } catch (Exception $e) {
                $this->messageManager->addExceptionMessage(
                    $e,
                    __(
                        'Something went wrong while saving the Invoice.'
                    )
                );
            }

            $this->dataPersistor->set(
                'gubee_integration_invoice',
                $data
            );
            return $resultRedirect->setPath(
                '*/*/edit',
                [
                    'invoice_id' => $this->getRequest()
                        ->getParam('invoice_id'),
                ]
            );
        }
        return $resultRedirect->setPath('*/*/');
    }
}
