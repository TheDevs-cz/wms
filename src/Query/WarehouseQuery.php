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

    /**
     * @return array<string, int>
     */
    public function positionsCount(): array
    {
        $sql = <<<SQL
SELECT w.id AS warehouse_id, COUNT(p.id) AS total_positions
FROM warehouse w
LEFT JOIN location l ON w.id = l.warehouse_id
LEFT JOIN position p ON l.id = p.location_id
GROUP BY w.id
SQL;

        $result = $this->entityManager
            ->getConnection()
            ->fetchAllAssociative($sql);

        /** @var array<string, int> $positionsPerWarehouse */
        $positionsPerWarehouse = [];

        foreach ($result as $row) {
            /**
             * @var array{warehouse_id: string, total_positions: int} $row
             */

            $positionsPerWarehouse[$row['warehouse_id']] = (int) $row['total_positions'];
        }

        return $positionsPerWarehouse;
    }
}
