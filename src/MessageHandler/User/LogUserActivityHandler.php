<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\User;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Exceptions\UserNotFound;
use TheDevs\WMS\Message\User\LogUserActivity;
use TheDevs\WMS\Repository\UserRepository;

#[AsMessageHandler]
readonly final class LogUserActivityHandler
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    public function __invoke(LogUserActivity $message): void
    {
        try {
            $user = $this->userRepository->getById($message->userId);

            if ($message->time->getTimestamp() > (($user->lastActivity?->getTimestamp() ?? 0) + 30)) {
                $user->refreshLastActivity($message->time);
            }
        } catch (UserNotFound) {
            // ... do nothing
        }
    }
}
