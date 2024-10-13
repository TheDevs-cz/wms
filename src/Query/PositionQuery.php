<?php

declare(strict_types=1);

namespace TheDevs\WMS\Query;

use Doctrine\ORM\EntityManagerInterface;
use TheDevs\WMS\Entity\Position;

readonly final class PositionQuery
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @return array<Position>
     */
    public function getAll(): array
    {
        return $this->entityManager->createQueryBuilder()
            ->from(Position::class, 'p')
            ->select('p')
            ->getQuery()
            ->getResult();
    }
}
