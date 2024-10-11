<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\User;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use TheDevs\WMS\Message\User\ChangePassword;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use TheDevs\WMS\Repository\UserRepository;

#[AsMessageHandler]
readonly final class ChangePasswordHandler
{
    public function __construct(
        private TokenStorageInterface $tokenStorage,
        private UserPasswordHasherInterface $passwordHasher,
        private UserRepository $userRepository,
    ) {
    }

    public function __invoke(ChangePassword $message): void
    {
        $user = $this->userRepository->get($message->userEmail);

        $hashedPassword = $this->passwordHasher->hashPassword($user, $message->newPlainTextPassword);

        $user->changePassword($hashedPassword);

        $token = new UsernamePasswordToken($user, 'main', $user->getRoles());
        $this->tokenStorage->setToken($token);
    }
}
