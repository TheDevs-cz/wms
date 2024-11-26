<?php

declare(strict_types=1);

namespace TheDevs\WMS\Query;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use Ramsey\Uuid\UuidInterface;
use TheDevs\WMS\Entity\OrderItem;
use TheDevs\WMS\Exceptions\OrderItemNotFound;

readonly final class OrderItemQuery
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @throws OrderItemNotFound
     */
    public function getByEanForOrder(string $ean, UuidInterface $orderId): OrderItem
    {
        try {
            /** @var OrderItem $row */
            $row = $this->entityManager->createQueryBuilder()
                ->from(OrderItem::class, 'oi')
                ->select('oi')
                ->where('oi.order = :orderId')
                ->setParameter('orderId', $orderId)
                ->andWhere('oi.ean = :ean')
                ->setParameter('ean', $ean)
                ->getQuery()
                ->getSingleResult();

            return $row;
        } catch (NoResultException $e) {
            throw new OrderItemNotFound(previous: $e);
        }
    }

    /**
     * @return array<OrderItem>
     */
    public function get(): array
    {
        $row = $this->entityManager->createQueryBuilder()
            ->from(OrderItem::class, 'oi')
            ->select('oi')
            ->join('oi.order', 'o')
            ->leftJoin('oi.product', 'p')
            ->where('o.status = :orderId')
            ->setParameter('orderId', $orderId)
            ->andWhere('oi.ean = :ean')
            ->setParameter('ean', $ean)
            ->getQuery()
            ->getSingleResult();

    }
}
