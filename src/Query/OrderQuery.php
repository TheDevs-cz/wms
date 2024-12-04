<?php

declare(strict_types=1);

namespace TheDevs\WMS\Query;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use Ramsey\Uuid\UuidInterface;
use TheDevs\WMS\Entity\Order;
use TheDevs\WMS\Exceptions\OrderNotFound;
use TheDevs\WMS\Value\OrderStatus;

readonly final class OrderQuery
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @return array<Order>
     */
    public function getAll(): array
    {
        return $this->entityManager->createQueryBuilder()
            ->from(Order::class, 'o')
            ->leftJoin('o.items', 'oi')
            ->select('o, oi')
            ->orderBy('o.orderedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array<Order>
     */
    public function getUnfinished(): array
    {
        return $this->entityManager->createQueryBuilder()
            ->from(Order::class, 'o')
            ->leftJoin('o.items', 'oi')
            ->select('o, oi')
            ->where('o.status NOT IN (:finishedStatuses)')
            ->setParameter('finishedStatuses', [
                OrderStatus::Shipped,
                OrderStatus::Cancelled,
                OrderStatus::Returned,
            ])
            ->orderBy('o.expeditionDate')
            ->addOrderBy('o.orderedAt')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array<Order>
     */
    public function getFinished(): array
    {
        return $this->entityManager->createQueryBuilder()
            ->from(Order::class, 'o')
            ->leftJoin('o.items', 'oi')
            ->select('o, oi')
            ->where('o.status IN (:finishedStatuses)')
            ->setParameter('finishedStatuses', [
                OrderStatus::Shipped,
                OrderStatus::Cancelled,
                OrderStatus::Returned,
            ])
            ->orderBy('o.orderedAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws OrderNotFound
     */
    public function getByNumberForUser(UuidInterface $userId, string $number): Order
    {
        try {
            /** @var Order $row */
            $row = $this->entityManager->createQueryBuilder()
                ->from(Order::class, 'o')
                ->select('o')
                ->where('o.number = :number')
                ->setParameter('number', $number)
                ->andWhere('o.user = :userId')
                ->setParameter('userId', $userId)
                ->getQuery()
                ->getSingleResult();

            return $row;
        } catch (NoResultException $e) {
            throw new OrderNotFound(previous: $e);
        }
    }

    public function countOpenOrders(): int
    {
        return (int) $this->entityManager->createQueryBuilder()
            ->select('COUNT(o.id)')
            ->from(Order::class, 'o')
            ->where('o.status = :status')
            ->setParameter('status', OrderStatus::Open)
            ->getQuery()
            ->getSingleScalarResult();
    }
}
