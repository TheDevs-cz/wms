<?php

declare(strict_types=1);

namespace TheDevs\WMS\Query;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use TheDevs\WMS\Entity\OrderHistory;

readonly final class OrderHistoryQuery
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @return array<OrderHistory>
     */
    public function getAll(int $limit = 30): array
    {
        return $this->entityManager->createQueryBuilder()
            ->from(OrderHistory::class, 'oh')
            ->select('oh')
            ->orderBy('oh.happenedAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array<OrderHistory>
     */
    public function getForOrder(UuidInterface $orderId): array
    {
        return $this->entityManager->createQueryBuilder()
            ->from(OrderHistory::class, 'oh')
            ->select('oh')
            ->where('oh.order = :orderId')
            ->setParameter('orderId', $orderId)
            ->orderBy('oh.happenedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }
}
