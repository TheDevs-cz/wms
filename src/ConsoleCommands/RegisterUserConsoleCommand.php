<?php

declare(strict_types=1);

namespace TheDevs\WMS\ConsoleCommands;

use TheDevs\WMS\Message\User\RegisterUser;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand('app:user:register')]
final class RegisterUserConsoleCommand extends Command
{
    public function __construct(
        readonly private MessageBusInterface $messageBus,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument('email', InputArgument::REQUIRED, 'New user email');
        $this->addArgument('password', InputArgument::REQUIRED, 'Plain text password');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var string $email */
        $email = $input->getArgument('email');

        /** @var string $plainTextPassword */
        $plainTextPassword = $input->getArgument('password');

        $this->messageBus->dispatch(
            new RegisterUser(
                $email,
                $plainTextPassword,
                null,
            ),
        );

        $output->writeln('<info>User successfully registered</info>');

        return self::SUCCESS;
    }
}
