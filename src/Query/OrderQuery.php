<?php

declare(strict_types=1);

namespace TheDevs\WMS\Query;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use Ramsey\Uuid\UuidInterface;
use TheDevs\WMS\Entity\Order;
use TheDevs\WMS\Exceptions\OrderNotFound;

readonly final class OrderQuery
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
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
}
