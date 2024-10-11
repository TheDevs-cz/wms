<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\User;

use TheDevs\WMS\Exceptions\UserNotFound;
use TheDevs\WMS\Message\User\EditUserProfile;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Repository\UserRepository;

#[AsMessageHandler]
readonly final class EditUserProfileHandler
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    /**
     * @throws UserNotFound
     */
    public function __invoke(EditUserProfile $message): void
    {
        $user = $this->userRepository->get($message->userEmail);

        $user->editProfile(
            $message->name,
        );
    }
}
