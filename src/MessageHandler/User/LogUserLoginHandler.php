<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\User;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Exceptions\UserNotFound;
use TheDevs\WMS\Message\User\LogUserActivity;
use TheDevs\WMS\Message\User\LogUserLogin;
use TheDevs\WMS\Repository\UserRepository;

#[AsMessageHandler]
readonly final class LogUserLoginHandler
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    public function __invoke(LogUserLogin $message): void
    {
        try {
            $user = $this->userRepository->getById($message->userId);
            $user->hasLoggedIn($message->time);
        } catch (UserNotFound) {
            // ... do nothing
        }
    }
}
