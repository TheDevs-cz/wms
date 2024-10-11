<?php

declare(strict_types=1);

namespace TheDevs\WMS\Repository;

use TheDevs\WMS\Entity\PasswordResetToken;
use Doctrine\ORM\EntityManagerInterface;
use TheDevs\WMS\Exceptions\InvalidPasswordResetToken;

readonly final class PasswordResetTokenRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function save(PasswordResetToken $token): void
    {
        $this->entityManager->persist($token);
    }

    /**
     * @throws InvalidPasswordResetToken
     */
    public function get(string $tokenId): PasswordResetToken
    {
        $token = $this->entityManager->find(PasswordResetToken::class, $tokenId);

        if ($token instanceof PasswordResetToken) {
            return $token;
        }

        throw new InvalidPasswordResetToken();
    }
}
