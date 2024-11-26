<?php

declare(strict_types=1);

namespace TheDevs\WMS\Query;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use TheDevs\WMS\Entity\Position;
use TheDevs\WMS\Entity\StockItem;

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

    /**
     * @return array<Position>
     */
    public function getByWarehouse(UuidInterface $warehouseId): array
    {
        return $this->entityManager->createQueryBuilder()
            ->from(Position::class, 'p')
            ->select('p, l')
            ->join('p.location', 'l')
            ->where('l.warehouse = :warehouseId')
            ->setParameter('warehouseId', $warehouseId)
            ->getQuery()
            ->getResult();
    }
}
