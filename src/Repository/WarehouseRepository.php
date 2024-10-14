<?php

declare(strict_types=1);

namespace TheDevs\WMS\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use TheDevs\WMS\Entity\Warehouse;
use TheDevs\WMS\Exceptions\WarehouseNotFound;

readonly final class WarehouseRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @throws WarehouseNotFound
     */
    public function get(UuidInterface $id): Warehouse
    {
        $warehouse = $this->entityManager->find(Warehouse::class, $id);

        if ($warehouse instanceof Warehouse) {
            return $warehouse;
        }

        throw new WarehouseNotFound();
    }

    public function add(Warehouse $warehouse): void
    {
        $this->entityManager->persist($warehouse);
    }
}
