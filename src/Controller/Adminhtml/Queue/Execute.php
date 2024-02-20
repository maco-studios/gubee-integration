<?php

declare(strict_types=1);

namespace Gubee\Integration\Controller\Adminhtml\Queue;

use Exception;
use Gubee\Integration\Api\QueueRepositoryInterface;
use Gubee\Integration\Model\Queue\Manager;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

use function __;

class Execute extends Action
{
    protected Manager $manager;

    protected QueueRepositoryInterface $queueRepository;

    public function __construct(
        Context $context,
        Manager $manager,
        QueueRepositoryInterface $queueRepository
    ) {
        parent::__construct($context);
        $this->manager         = $manager;
        $this->queueRepository = $queueRepository;
    }

    public function execute()
    {
        try {
            if (! $this->getRequest()->getParam('id')) {
                throw new Exception(
                    __('Queue item ID not provided.')
                );
            }
            $queue = $this->getQueueRepository()->getById(
                (int) $this->getRequest()->getParam('id')
            );
            if (! $queue->getId()) {
                throw new Exception(
                    __('Queue item not found.')
                );
            }
            $this->getManager()->process($queue);
            $this->messageManager->addSuccessMessage(
                __('Queue item processed.')
            );
        } catch (Exception $e) {
            $this->messageManager->addErrorMessage(
                $e->getMessage()
            );
        } finally {
            $this->_redirect('gubee_integration/queue/index');
        }
    }

    public function getManager(): Manager
    {
        return $this->manager;
    }

    public function getQueueRepository(): QueueRepositoryInterface
    {
        return $this->queueRepository;
    }
}
