<?php

declare(strict_types=1);

namespace Gubee\Integration\Model\Queue;

use Exception;
use Gubee\Integration\Api\Data\QueueInterface;
use Gubee\Integration\Api\QueueRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\ObjectManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

use function json_decode;
use function sprintf;

use const PHP_EOL;

class Manager
{
    protected LoggerInterface $logger;

    protected QueueRepositoryInterface $queueRepository;

    protected ObjectManagerInterface $objectManager;

    protected SearchCriteriaBuilder $searchCriteriaBuilder;

    public function __construct(
        LoggerInterface $logger,
        QueueRepositoryInterface $queueRepository,
        ObjectManagerInterface $objectManager,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->logger                = $logger;
        $this->queueRepository       = $queueRepository;
        $this->objectManager         = $objectManager;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    /**
     * Process a single queue item.
     *
     * @param QueueInterface $queueItem The queue item to process.
     * @throws Exception If the queue item has exceeded the maximum attempts.
     */
    public function process(QueueInterface $queueItem)
    {
        try {
            $this->checkAttempts($queueItem);
            $this->startProcessing($queueItem);
            $this->runHandler($queueItem);
            $this->markSuccess($queueItem);
        } catch (Exception $e) {
            $this->handleError($queueItem, $e);
        } finally {
            $this->finalizeProcessing($queueItem);
        }
    }

    /**
     * Check if the queue item has exceeded the maximum attempts.
     *
     * @param QueueInterface $queueItem The queue item to check.
     * @throws Exception If the queue item has exceeded the maximum attempts.
     */
    protected function checkAttempts(QueueInterface $queueItem)
    {
        if ($this->exceedAttempts($queueItem)) {
            throw new Exception(
                sprintf(
                    "Queue item with id '%s' has exceeded the maximum attempts",
                    $queueItem->getId()
                )
            );
        }
    }

    /**
     * Start processing the queue item.
     *
     * @param QueueInterface $queueItem The queue item to process.
     */
    protected function startProcessing(QueueInterface $queueItem)
    {
        $this->updateStatus($queueItem, QueueInterface::STATUS_RUNNING);
        $this->getLogger()->info(
            sprintf(
                "Processing queue item with id '%s'",
                $queueItem->getId()
            )
        );
    }

    /**
     * Run the handler for the queue item.
     *
     * @param QueueInterface $queueItem The queue item to process.
     */
    protected function runHandler(QueueInterface $queueItem)
    {
        $params = json_decode($queueItem->getPayload(), true);
        $output = $this->getObjectManager()->create(BufferedOutput::class);
        $input  = $this->getObjectManager()->create(ArrayInput::class, $params);

        $this->getObjectManager()
            ->create($queueItem->getHandler())
            ->run($input, $output);

        $queueItem->setResponse($output->fetch());
    }

    /**
     * Mark the queue item as successful.
     *
     * @param QueueInterface $queueItem The queue item to mark as successful.
     */
    protected function markSuccess(QueueInterface $queueItem)
    {
        $this->updateStatus($queueItem, QueueInterface::STATUS_SUCCESS);
    }

    /**
     * Handle an error that occurred during processing of the queue item.
     *
     * @param QueueInterface $queueItem The queue item that encountered the error.
     * @param Exception $e The exception that occurred.
     */
    protected function handleError(QueueInterface $queueItem, Exception $e)
    {
        $this->getLogger()->error($e->getMessage());
        $this->updateStatus($queueItem, QueueInterface::STATUS_ERROR);
        $queueItem->setErrorMessage($queueItem->getErrorMessage() . PHP_EOL . $e->getMessage());
    }

    /**
     * Finalize the processing of the queue item.
     *
     * @param QueueInterface $queueItem The queue item to finalize processing.
     */
    protected function finalizeProcessing(QueueInterface $queueItem)
    {
        $queueItem->setAttempts($queueItem->getAttempts() + 1);
        $this->getQueueRepository()->save($queueItem);
    }

    /**
     * Process multiple queue items.
     *
     * @param iterable $queueItems The queue items to process.
     */
    public function massProcess(iterable $queueItems)
    {
        foreach ($queueItems as $queueItem) {
            $this->process($queueItem);
        }
    }

    /**
     * Get the list of pending queue items.
     *
     * @return iterable The list of pending queue items.
     */
    public function getPendingList(): iterable
    {
        $searchCriteria = $this->getSearchCriteriaBuilder()
            ->addFilter(
                'status',
                QueueInterface::STATUS_PENDING
            )
            ->create();

        return $this->getQueueRepository()
            ->getList($searchCriteria)
            ->getItems();
    }

    /**
     * Get the list of queue items to be retried.
     *
     * @return iterable The list of queue items to be retried.
     */
    public function getToBeRetriedList(): iterable
    {
        $searchCriteria = $this->getSearchCriteriaBuilder()
            ->addFilter(
                'status',
                QueueInterface::STATUS_ERROR
            )
            ->create();

        return $this->getQueueRepository()
            ->getList($searchCriteria)
            ->getItems();
    }

    /**
     * Check if a queue item has exceeded the maximum attempts.
     *
     * @param QueueInterface $queueItem The queue item to check.
     * @return bool True if the queue item has exceeded the maximum attempts, false otherwise.
     */
    public function exceedAttempts(QueueInterface $queueItem): bool
    {
        return $queueItem->getAttempts() >= $queueItem->getMaxAttempts();
    }

    /**
     * Update the status of a queue item.
     *
     * @param QueueInterface $queueItem The queue item to update.
     * @param int $status The new status value.
     */
    public function updateStatus(QueueInterface $queueItem, int $status)
    {
        try {
            $queueItem->setStatus($status);
            $this->getQueueRepository()->save($queueItem);
        } catch (Exception $e) {
            $this->getLogger()->error($e->getMessage());
        }
    }

    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    public function getQueueRepository(): QueueRepositoryInterface
    {
        return $this->queueRepository;
    }

    public function getObjectManager(): ObjectManagerInterface
    {
        return $this->objectManager;
    }

    public function getSearchCriteriaBuilder(): Magento\Framework\Api\SearchCriteriaBuilder
    {
        return $this->searchCriteriaBuilder;
    }
}
