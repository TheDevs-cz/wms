<?php

declare(strict_types=1);

namespace TheDevs\WMS\Repository;

use Doctrine\ORM\EntityManagerInterface;
use TheDevs\WMS\Entity\OrderItem;

readonly final class OrderItemRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function add(OrderItem $item): void
    {
        $this->entityManager->persist($item);
    }
}
