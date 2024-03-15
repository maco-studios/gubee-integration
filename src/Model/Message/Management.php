<?php

declare(strict_types=1);

namespace Gubee\Integration\Model\Message;

use Gubee\Integration\Api\Data\MessageInterface;
use Gubee\Integration\Api\Enum\Message\StatusEnum;
use Gubee\Integration\Api\Message\ManagementInterface;
use Gubee\Integration\Api\MessageRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Filesystem\Driver\File as FileDriver;
use Magento\Framework\ObjectManagerInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

class Management implements ManagementInterface
{
    protected DateTime $date;
    protected FileDriver $fileDriver;
    protected LoggerInterface $logger;
    protected MessageRepositoryInterface $messageRepository;
    protected ObjectManagerInterface $objectManager;
    protected ScopeConfigInterface $scopeConfig;
    protected SearchCriteriaBuilder $searchCriteriaBuilder;

    public function __construct(
        DateTime $date,
        LoggerInterface $logger,
        MessageRepositoryInterface $messageRepository,
        ObjectManagerInterface $objectManager,
        ScopeConfigInterface $scopeConfig,
        SearchCriteriaBuilder $searchCriteriaBuilder
    ) {
        $this->date                  = $date;
        $this->logger                = $logger;
        $this->messageRepository     = $messageRepository;
        $this->objectManager         = $objectManager;
        $this->scopeConfig           = $scopeConfig;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
    }

    public function process(MessageInterface $message): void
    {
        $status = StatusEnum::ERROR();
        // try {
        $message->setAttempts(
            $message->getAttempts() + 1
        );
        $this->updateMessageStatus(
            $message,
            StatusEnum::RUNNING()
        );
        $this->execute($message);
        $status = StatusEnum::DONE();
        // } catch (ErrorException $e) {
        //     $result = __(
        //         "EXCEPTION: '%1', check the %s for more details.",
        //         'var/log/exception.log',
        //         (string) $e->getResponse()->getBody()
        //     );
        //     $status = StatusEnum::ERROR();

        //     $this->logger->error(
        //         $result,
        //         [
        //             'exception' => $e,
        //         ]
        //     );
        //     $message->setMessage(
        //         (string) $result
        //     );
        // } catch (Throwable $e) {
        //     $result = __(
        //         "EXCEPTION: '%1', check the %s for more details.",
        //         'var/log/exception.log',
        //         $e->getMessage()
        //     );
        //     $status = StatusEnum::ERROR();

        //     $this->logger->error(
        //         $result,
        //         [
        //             'exception' => $e,
        //         ]
        //     );
        //     $message->setMessage(
        //         (string) $result
        //     );
        // } finally {
        //     $this->updateMessageStatus($message, $status);
        // }
    }

    protected function execute(MessageInterface $message): void
    {
        $command = $this->objectManager->create(
            $message->getCommand()
        );
        $input   = $this->objectManager->create(
            ArrayInput::class,
            [
                'parameters' => $message->getPayload(),
            ]
        );
        $output  = $this->objectManager->create(
            BufferedOutput::class
        );
        $command->run($input, $output);
        $message->setMessage(
            (string) $output->fetch()
        );
    }

    private function updateMessageStatus(MessageInterface $message, StatusEnum $status): void
    {
        $message->setStatus($status);
        $this->messageRepository->save($message);
    }

    public function getPending()
    {
        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(
                MessageInterface::STATUS,
                (int) StatusEnum::PENDING()->__toString()
            )
            ->create();

        return $this->messageRepository->getList(
            $searchCriteria
        );
    }

    public function getToBeRetried(): array
    {
        $retryAmount = $this->scopeConfig->getValue('queue/general/auto_retry_amount');
        if (! $retryAmount) {
            return [];
        }

        $searchCriteria = $this->searchCriteriaBuilder
            ->addFilter(MessageInterface::STATUS, (int) StatusEnum::ERROR()->__toString())
            ->addFilter(MessageInterface::ATTEMPTS, $retryAmount, 'lt')
            ->create();

        return $this->messageRepository->getList($searchCriteria)->getItems();
    }

    /**
     * Process a bunch of messages at once
     *
     * @param iterable<MessageInterface> $messages
     */
    public function massProcess(iterable $messages): void
    {
        foreach ($messages as $message) {
            $this->process($message);
        }
    }
}
