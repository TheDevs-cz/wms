<?php

declare(strict_types=1);

namespace TheDevs\WMS\Query;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use TheDevs\WMS\Entity\StockMovement;

readonly final class StockMovementQuery
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @return array<StockMovement>
     */
    public function getForProduct(UuidInterface $productId, int $limit = 30): array
    {
        return $this->entityManager->createQueryBuilder()
            ->from(StockMovement::class, 'sm')
            ->select('sm')
            ->where('sm.product = :productId')
            ->setParameter('productId', $productId)
            ->orderBy('sm.movedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array<StockMovement>
     */
    public function getForWarehouse(UuidInterface $warehouseId, int $limit = 30): array
    {
        return $this->entityManager->createQueryBuilder()
            ->from(StockMovement::class, 'sm')
            ->select('sm')
            ->leftJoin('sm.fromPosition', 'fromPos')
            ->leftJoin('sm.toPosition', 'toPos')
            ->leftJoin('fromPos.location', 'fromLocation')
            ->leftJoin('toPos.location', 'toLocation')
            ->leftJoin('fromLocation.warehouse', 'fromWarehouse')
            ->leftJoin('toLocation.warehouse', 'toWarehouse')
            ->where('(fromWarehouse IS NOT NULL AND fromWarehouse.id = :warehouseId) OR (toWarehouse IS NOT NULL AND toWarehouse.id = :warehouseId)')
            ->setParameter('warehouseId', $warehouseId)
            ->orderBy('sm.movedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array<StockMovement>
     */
    public function getForLocation(UuidInterface $locationId, int $limit = 30): array
    {
        return $this->entityManager->createQueryBuilder()
            ->from(StockMovement::class, 'sm')
            ->select('sm')
            ->leftJoin('sm.fromPosition', 'fromPos')
            ->leftJoin('sm.toPosition', 'toPos')
            ->where('(fromPos IS NOT NULL AND fromPos.location = :locationId) OR (toPos IS NOT NULL AND toPos.location = :locationId)')
            ->setParameter('locationId', $locationId)
            ->orderBy('sm.movedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array<StockMovement>
     */
    public function getForPosition(UuidInterface $positionId, int $limit = 30): array
    {
        return $this->entityManager->createQueryBuilder()
            ->from(StockMovement::class, 'sm')
            ->select('sm')
            ->where('sm.fromPosition = :positionId OR sm.toPosition = :positionId')
            ->setParameter('positionId', $positionId)
            ->orderBy('sm.movedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array<StockMovement>
     */
    public function getForOrder(UuidInterface $orderId, int $limit = 30): array
    {
        return $this->entityManager->createQueryBuilder()
            ->from(StockMovement::class, 'sm')
            ->select('sm')
            ->where('sm.order = :orderId')
            ->setParameter('orderId', $orderId)
            ->orderBy('sm.movedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
