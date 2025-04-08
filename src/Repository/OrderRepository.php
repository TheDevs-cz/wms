<?php

declare(strict_types=1);

namespace TheDevs\WMS\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use TheDevs\WMS\Entity\Order;
use TheDevs\WMS\Exceptions\OrderNotFound;

readonly final class OrderRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @throws OrderNotFound
     */
    public function get(UuidInterface $id): Order
    {
        $order = $this->entityManager->find(Order::class, $id);

        if ($order instanceof Order) {
            return $order;
        }

        throw new OrderNotFound();
    }

    /**
     * @throws OrderNotFound
     */
    public function getByNumber(UuidInterface $id): Order
    {
        $order = $this->entityManager->find(Order::class, $id);

        if ($order instanceof Order) {
            return $order;
        }

        throw new OrderNotFound();
    }

    public function add(Order $order): void
    {
        $this->entityManager->persist($order);
    }
}
