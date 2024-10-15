<?php

declare(strict_types=1);

namespace TheDevs\WMS\Query;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
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
    public function getForProduct(UuidInterface $productId): array
    {
        return $this->entityManager->createQueryBuilder()
            ->from(StockMovement::class, 'sm')
            ->select('sm')
            ->where('sm.product = :productId')
            ->setParameter('productId', $productId)
            ->orderBy('sm.movedAt', 'DESC')
            ->getQuery()
            ->setFetchMode(StockMovement::class, 'position', ClassMetadata::FETCH_EAGER)
            ->getResult();
    }
}
