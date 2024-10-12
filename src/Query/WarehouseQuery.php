<?php

declare(strict_types=1);

namespace TheDevs\WMS\Query;

use Doctrine\ORM\EntityManagerInterface;
use TheDevs\WMS\Entity\Warehouse;

readonly final class WarehouseQuery
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @return array<Warehouse>
     */
    public function getAll(): array
    {
        return $this->entityManager->createQueryBuilder()
            ->from(Warehouse::class, 'w')
            ->select('w')
            ->getQuery()
            ->getResult();
    }
}
