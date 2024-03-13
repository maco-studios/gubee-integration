<?php

declare(strict_types=1);

namespace Gubee\Integration\Command;

use Gubee\Integration\Api\CommandInterface;
use InvalidArgumentException;
use Symfony\Component\Console\Command\Command;

use function sprintf;

abstract class AbstractCommand extends Command implements CommandInterface
{
    /**
     * Sets the name of the command.
     *
     * This method can set both the namespace and the name if
     * you separate them by a colon (:)
     *
     *     $command->setName('foo:bar');
     *
     * @param string $name The command name
     * @return $this
     * @throws InvalidArgumentException When the name is invalid.
     */
    public function setName($name): self
    {
        return parent::setName(
            sprintf(
                "gubee:%s",
                $name
            )
        );
    }

    /**
     * Gets the maximum number of attempts to execute the command.
     */
    public function getMaxAttempts(): int
    {
        return 3;
    }
}
