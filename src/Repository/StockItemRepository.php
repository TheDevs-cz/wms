<?php

declare(strict_types=1);

namespace TheDevs\WMS\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use TheDevs\WMS\Entity\StockItem;
use TheDevs\WMS\Exceptions\StockItemNotFound;

readonly final class StockItemRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @throws StockItemNotFound
     */
    public function get(UuidInterface $id): StockItem
    {
        $stockItem = $this->entityManager->find(StockItem::class, $id);

        if ($stockItem instanceof StockItem) {
            return $stockItem;
        }

        throw new StockItemNotFound();
    }

    public function add(StockItem $stockItem): void
    {
        $this->entityManager->persist($stockItem);
    }
}
