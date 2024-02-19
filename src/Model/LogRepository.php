<?php

declare(strict_types=1);

namespace Gubee\Integration\Model;

use Exception;
use Gubee\Integration\Api\Data\LogInterface;
use Gubee\Integration\Api\Data\LogInterfaceFactory;
use Gubee\Integration\Api\Data\LogSearchResultsInterfaceFactory;
use Gubee\Integration\Api\LogRepositoryInterface;
use Gubee\Integration\Model\ResourceModel\Log as ResourceLog;
use Gubee\Integration\Model\ResourceModel\Log\CollectionFactory as LogCollectionFactory;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotDeleteException;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;

use function __;

class LogRepository implements LogRepositoryInterface
{
    /** @var CollectionProcessorInterface */
    protected $collectionProcessor;

    /** @var LogInterfaceFactory */
    protected $logFactory;

    /** @var LogCollectionFactory */
    protected $logCollectionFactory;

    /** @var ResourceLog */
    protected $resource;

    /** @var Log */
    protected $searchResultsFactory;

    public function __construct(
        ResourceLog $resource,
        LogInterfaceFactory $logFactory,
        LogCollectionFactory $logCollectionFactory,
        LogSearchResultsInterfaceFactory $searchResultsFactory,
        CollectionProcessorInterface $collectionProcessor
    ) {
        $this->resource             = $resource;
        $this->logFactory           = $logFactory;
        $this->logCollectionFactory = $logCollectionFactory;
        $this->searchResultsFactory = $searchResultsFactory;
        $this->collectionProcessor  = $collectionProcessor;
    }

    /**
     * @inheritDoc
     */
    public function save(LogInterface $log)
    {
        try {
            $this->resource->save($log);
        } catch (Exception $exception) {
            throw new CouldNotSaveException(__(
                'Could not save the log: %1',
                $exception->getMessage()
            ));
        }
        return $log;
    }

    /**
     * @inheritDoc
     */
    public function get($logId)
    {
        $log = $this->logFactory->create();
        $this->resource->load($log, $logId);
        if (! $log->getId()) {
            throw new NoSuchEntityException(__('Log with id "%1" does not exist.', $logId));
        }
        return $log;
    }

    /**
     * @inheritDoc
     */
    public function getList(
        SearchCriteriaInterface $criteria
    ) {
        $collection = $this->logCollectionFactory->create();

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
    public function delete(LogInterface $log)
    {
        try {
            $logModel = $this->logFactory->create();
            $this->resource->load($logModel, $log->getLogId());
            $this->resource->delete($logModel);
        } catch (Exception $exception) {
            throw new CouldNotDeleteException(__(
                'Could not delete the Log: %1',
                $exception->getMessage()
            ));
        }
        return true;
    }

    /**
     * @inheritDoc
     */
    public function deleteById($logId)
    {
        return $this->delete($this->get($logId));
    }
}
