<?php

declare(strict_types=1);

namespace Gubee\Integration\Model;

use Exception;
use Gubee\Integration\Api\Data\QueueInterface;
use Gubee\Integration\Api\Data\QueueInterfaceFactory;
use Gubee\Integration\Api\Data\QueueSearchResultsInterfaceFactory;
use Gubee\Integration\Api\QueueRepositoryInterface;
use Gubee\Integration\Model\ResourceModel\Queue as ResourceQueue;
use Gubee\Integration\Model\ResourceModel\Queue\CollectionFactory as QueueCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

use function __;

class QueueRepository implements QueueRepositoryInterface
{
    /** @var QueueCollectionFactory */
    protected $queueCollectionFactory;

    /** @var ResourceQueue */
    protected $resource;

    /** @var CollectionProcessorInterface */
    protected $collectionProcessor;

    /** @var Queue */
    protected $searchResultsFactory;

    /** @var QueueInterfaceFactory */
    protected $queueFactory;

    public function __construct(
        ResourceQueue $resource,
        QueueInterfaceFactory $queueFactory,
        QueueCollectionFactory $queueCollectionFactory,
        QueueSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource               = $resource;
        $this->queueFactory           = $queueFactory;
        $this->queueCollectionFactory = $queueCollectionFactory;
        $this->searchResultsFactory   = $searchResultsFactory;
        $this->collectionProcessor    = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(QueueInterface $queue)
    {
        try {
            $this->resource->save($queue);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the queue: %1',
                $exception->getMessage()
            ));
        }
        return $queue;
    }

    /**
     * @inheritDoc
     */
    public function get($queueId)
    {
        $queue = $this->queueFactory->create();
        $this->resource->load($queue, $queueId);
        if (! $queue->getId()) {
            throw new NoSuchEntityException(__('Queue with id "%1" does not exist.', $queueId));
        }
        return $queue;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        SearchCriteriaInterface $criteria
    ) {
        $collection = $this->queueCollectionFactory->create();

        $this->collectionProcessor->process($criteria, $collection);

        $searchResults = $this->searchResultsFactory->create();
        $searchResults->setSearchCriteria($criteria);

        $items = [];
        foreach ($collection as $model) {
            $items[] = $model;
        }

        $searchResults->setItems($items);
        $searchResults->setTotalCount($collection->getSize());
        return $searchResults;
    }

    /**
     * @inheritDoc
     */
    public function delete(QueueInterface $queue)
    {
        try {
            $queueModel = $this->queueFactory->create();
            $this->resource->load($queueModel, $queue->getQueueId());
            $this->resource->delete($queueModel);
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Queue: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($queueId)
    {
        return $this->delete($this->get($queueId));
    }
}
