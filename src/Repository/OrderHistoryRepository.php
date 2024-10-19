<?php

declare(strict_types=1);

namespace TheDevs\WMS\Repository;

use Doctrine\ORM\EntityManagerInterface;
use TheDevs\WMS\Entity\OrderHistory;

readonly final class OrderHistoryRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function add(OrderHistory $history): void
    {
        $this->entityManager->persist($history);
    }
}
