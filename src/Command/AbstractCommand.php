<?php

declare(strict_types=1);

namespace Gubee\Integration\Command;

use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Input\InputInterface;
use Magento\Framework\Event\ManagerInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Exception\ExceptionInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Exception\InvalidArgumentException;

abstract class AbstractCommand extends Command
{
    protected InputInterface $input;
    protected ManagerInterface $eventDispatcher;
    protected LoggerInterface $logger;
    protected OutputInterface $output;

    /**
     * @param string|null $name The name of the command; passing null means it must be set in configure()
     *
     * @throws LogicException When the command name is empty
     */
    public function __construct(
        ManagerInterface $eventDispatcher,
        LoggerInterface $logger,
        string $name = null
    )
    {
        $this->eventDispatcher = $eventDispatcher;
        $this->logger = $logger;
        parent::__construct($name);
    }

    /**
     * Runs the command.
     *
     * The code to execute is either defined directly with the
     * setCode() method or by overriding the execute() method
     * in a sub-class.
     *
     * @return int The command exit code
     *
     * @throws ExceptionInterface When input binding fails. Bypass this by calling {@link ignoreValidationErrors()}.
     *
     * @see setCode()
     * @see execute()
     */
    public function run(InputInterface $input, OutputInterface $output)
    {
        $this->input = $input;
        $this->output = $output;
        $this->getEventDispatcher()->dispatch(
            sprintf(
                "gubee.command.%s.run.before",
                $this->getName()
            ),
            [
                'command' => $this,
                'input' => $input,
                'output' => $output
            ]
        );

        $result = parent::run($input, $output);
        $this->getEventDispatcher()->dispatch(
            sprintf(
                "gubee.command.%s.run.after",
                $this->getName()
            ),
            [
                'command' => $this,
                'input' => $input,
                'output' => $output,
                'result' => $result
            ]
        );

        return $result;
    }

    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @return int 0 if everything went fine, or an exit code
     *
     * @throws LogicException When this abstract method is not implemented
     *
     * @see setCode()
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->output = $output;
        $this->input = $input;
        $this->getLogger()->info(
            sprintf(
                "Running command '%s'",
                $this->getName()
            )
        );

        $this->getEventDispatcher()->dispatch(
            sprintf(
                "gubee.command.%s.execute.before",
                $this->getName()
            ),
            [
                'command' => $this,
                'input' => $input,
                'output' => $output
            ]
        );

        $result = $this->doExecute($input, $output);

        $this->getEventDispatcher()->dispatch(
            sprintf(
                "gubee.command.%s.execute.after",
                $this->getName()
            ),
            [
                'command' => $this,
                'input' => $input,
                'output' => $output,
                'result' => $result
            ]
        );

        return $result;
    }


    /**
     * Sets the name of the command.
     *
     * This method can set both the namespace and the name if
     * you separate them by a colon (:)
     *
     *     $command->setName('foo:bar');
     *
     * @param string $name The command name
     *
     * @return $this
     *
     * @throws InvalidArgumentException When the name is invalid
     */

    public function setName($name)
    {
        return parent::setName(
            sprintf(
                '%s:%s',
                'gubee',
                $name
            )
        );
    }




    /**
     * @return InputInterface
     */
    public function getInput(): InputInterface
    {
        return $this->input;
    }

    /**
     * @return ManagerInterface
     */
    public function getEventDispatcher(): ManagerInterface
    {
        return $this->eventDispatcher;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger(): LoggerInterface
    {
        return $this->logger;
    }

    /**
     * @return OutputInterface
     */
    public function getOutput(): OutputInterface
    {
        return $this->output;
    }
}
