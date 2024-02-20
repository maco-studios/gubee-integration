<?php

declare(strict_types=1);

namespace Gubee\Integration\Command\Gubee\Token;

use DateTimeInterface;
use Exception;
use Gubee\Integration\Command\AbstractCommand;
use Gubee\Integration\Helper\Config;
use Gubee\SDK\Gubee;
use Magento\Framework\Event\ManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputArgument;

class RenewCommand extends AbstractCommand
{
    protected Gubee $client;
    protected Config $config;

    /**
     * @param string|null $name The name of the command; passing null means it must be set in configure()
     * @throws LogicException When the command name is empty.
     */
    public function __construct(
        ManagerInterface $eventDispatcher,
        LoggerInterface $logger,
        Config $config,
        Gubee $client
    ) {
        $this->client = $client;
        $this->config = $config;
        parent::__construct(
            $eventDispatcher,
            $logger,
            "token:renew"
        );
    }

    /**
     * @inheritDoc
     */
    protected function configure()
    {
        $this->setDescription("Renew the token");
        $this->setHelp("This command will renew the token");
        $this->addArgument("token", InputArgument::REQUIRED, "The token to be renewed");
    }

    /**
     * Executes the command.
     */
    protected function doExecute(): int
    {
        $this->logger->info("Renewing token");
        try {
            $token = $this->getClient()->token()->revalidate(
                $this->input->getArgument("token")
            );
            $this->config->setApiTimeout(
                $token->getValidity()->format(DateTimeInterface::ISO8601)
            );
            $this->config->setApiToken(
                $token->getToken()
            );
            $this->config->save();
            $this->getLogger()->debug(
                "Token renewed successfully"
            );
        } catch (Exception $e) {
            $this->getLogger()->error(
                $e->getMessage()
            );
        }

        return self::SUCCESS;
    }

    public function getClient(): Gubee
    {
        return $this->client;
    }
}
