<?php

declare(strict_types=1);

namespace TheDevs\WMS\Repository;

use Doctrine\ORM\EntityManagerInterface;
use TheDevs\WMS\Entity\StockItem;

readonly final class StockItemRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function add(StockItem $stockItem): void
    {
        $this->entityManager->persist($stockItem);
    }
}
