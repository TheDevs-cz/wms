<?php

declare(strict_types=1);

namespace TheDevs\WMS\Query;

use Doctrine\ORM\EntityManagerInterface;
use TheDevs\WMS\Entity\User;

readonly final class UserQuery
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @return array<User>
     */
    public function getAll(): array
    {
        return $this->entityManager->createQueryBuilder()
            ->from(User::class, 'u')
            ->select('u')
            ->getQuery()
            ->getResult();
    }
}
