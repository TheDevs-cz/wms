<?php

declare(strict_types=1);

namespace TheDevs\WMS\Repository;

use Doctrine\ORM\EntityManagerInterface;
use TheDevs\WMS\Entity\Warehouse;

readonly final class WarehouseRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function save(Warehouse $warehouse): void
    {
        $this->entityManager->persist($warehouse);
    }
}
