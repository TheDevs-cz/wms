<?php

declare(strict_types=1);

namespace TheDevs\WMS\Repository;

use Doctrine\ORM\EntityManagerInterface;
use TheDevs\WMS\Entity\StockMovement;

readonly final class StockMovementRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function add(StockMovement $stockMovement): void
    {
        $this->entityManager->persist($stockMovement);
    }
}
