<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\User;

use Psr\Clock\ClockInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\Message\User\AddUser;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Repository\UserRepository;
use TheDevs\WMS\Services\ProvideIdentity;

#[AsMessageHandler]
readonly final class AddUserHandler
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private UserRepository $userRepository,
        private ProvideIdentity $provideIdentity,
        private ClockInterface $clock,
    ) {
    }

    public function __invoke(AddUser $message): void
    {
        $user = new User(
            $this->provideIdentity->next(),
            $message->email,
            $this->clock->now(),
            roles: [$message->role],
        );

        $hashedPassword = $this->passwordHasher->hashPassword($user, $message->plainTextPassword);

        $user->changePassword($hashedPassword);

        $this->userRepository->add($user);
    }
}
