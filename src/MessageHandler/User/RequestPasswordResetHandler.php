<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\User;

use Psr\Clock\ClockInterface;
use TheDevs\WMS\Entity\PasswordResetToken;
use TheDevs\WMS\Exceptions\UserNotFound;
use TheDevs\WMS\Exceptions\UserNotRegistered;
use TheDevs\WMS\Message\User\RequestPasswordReset;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Repository\PasswordResetTokenRepository;
use TheDevs\WMS\Repository\UserRepository;
use TheDevs\WMS\Services\ProvideIdentity;

#[AsMessageHandler]
readonly final class RequestPasswordResetHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private ProvideIdentity $provideIdentity,
        private PasswordResetTokenRepository $passwordResetTokenRepository,
        private ClockInterface $clock,
    ) {
    }

    /**
     * @throws UserNotRegistered
     */
    public function __invoke(RequestPasswordReset $message): void
    {
        try {
            $user = $this->userRepository->get($message->email);
        } catch (UserNotFound) {
            throw new UserNotRegistered();
        }

        $token = new PasswordResetToken(
            $this->provideIdentity->next(),
            $user,
            $this->clock->now(),
            $this->clock->now()->modify('+8 hours'),
        );

        $this->passwordResetTokenRepository->add($token);

        // TODO: send email
    }
}
