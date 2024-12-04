<?php

declare(strict_types=1);

namespace TheDevs\WMS\Query;

use Doctrine\ORM\EntityManagerInterface;
use TheDevs\WMS\QueryResult\MonthlyAccounting;
use TheDevs\WMS\Value\OrderStatus;

readonly final class AccountingQuery
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    function getMonthlyAccounting(): MonthlyAccounting
    {
        $sql = <<<SQL
        SELECT 
            u.id AS user_id,
            u.email,
            EXTRACT(YEAR FROM oh.happened_at)::INTEGER AS year,
            EXTRACT(MONTH FROM oh.happened_at)::INTEGER AS month,
            COUNT(oh.id) AS shipped_count
        FROM 
            order_history oh
        INNER JOIN 
            "order" o ON o.id = oh.order_id
        INNER JOIN 
            user u ON u.id = o.user_id
        WHERE 
            oh.to_status = :finalStatus
        GROUP BY 
            u.id, u.email, year, month
    SQL;

        /**
         * @var array<array{
         *      user_id: string,
         *      email: string,
         *      year: int,
         *      month: int,
         *      shipped_count: int,
         *  }> $results
         */
        $results = $this->entityManager->getConnection()
            ->executeQuery($sql, [
                'finalStatus' => OrderStatus::Shipped->value,
            ])
            ->fetchAllAssociative();

        return MonthlyAccounting::fromDatabaseResults($results);
    }
}
