<?php

declare(strict_types=1);

namespace TheDevs\WMS\Query;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Ramsey\Uuid\UuidInterface;
use TheDevs\WMS\Entity\StockItem;
use TheDevs\WMS\Exceptions\MultipleStockItemsFound;
use TheDevs\WMS\Exceptions\StockItemNotFound;
use TheDevs\WMS\QueryResult\StockDemand;

/**
 * @phpstan-import-type StockDemandData from StockDemand
 */
readonly final class StockItemQuery
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @throws StockItemNotFound
     */
    public function getByPositionAndEan(UuidInterface $positionId, string $ean): StockItem
    {
        try {
            /** @var StockItem $row */
            $row = $this->entityManager->createQueryBuilder()
                ->from(StockItem::class, 'si')
                ->select('si')
                ->where('si.ean = :ean')
                ->setParameter('ean', $ean)
                ->andWhere('si.position = :positionId')
                ->setParameter('positionId', $positionId)
                ->getQuery()
                ->getSingleResult();

            return $row;
        } catch (NoResultException $e) {
            throw new StockItemNotFound(previous: $e);
        }
    }

    /**
     * @throws StockItemNotFound
     */
    public function getByPositionAndEanOfUser(UuidInterface $positionId, string $ean, UuidInterface $userId): StockItem
    {
        try {
            /** @var StockItem $row */
            $row = $this->entityManager->createQueryBuilder()
                ->from(StockItem::class, 'si')
                ->select('si, p')
                ->join('si.product', 'p')
                ->where('si.ean = :ean')
                ->setParameter('ean', $ean)
                ->andWhere('si.position = :positionId')
                ->setParameter('positionId', $positionId)
                ->andWhere('p.user = :userId')
                ->setParameter('userId', $userId)
                ->getQuery()
                ->getSingleResult();

            return $row;
        } catch (NoResultException $e) {
            throw new StockItemNotFound(previous: $e);
        }
    }

    /**
     * @throws StockItemNotFound
     * @throws MultipleStockItemsFound
     */
    public function getByEanOfUser(string $ean, UuidInterface $userId): StockItem
    {
        try {
            /** @var StockItem $row */
            $row = $this->entityManager->createQueryBuilder()
                ->from(StockItem::class, 'si')
                ->select('si, p')
                ->join('si.product', 'p')
                ->where('si.ean = :ean')
                ->setParameter('ean', $ean)
                ->andWhere('p.user = :userId')
                ->setParameter('userId', $userId)
                ->andWhere('si.quantity > 0')
                ->getQuery()
                ->getSingleResult();

            return $row;
        } catch (NoResultException $e) {
            throw new StockItemNotFound(previous: $e);
        } catch (NonUniqueResultException $e) {
            throw new MultipleStockItemsFound(previous: $e);
        }
    }

    /**
     * @return array<StockItem>
     */
    public function findAllByEanOfUser(string $ean, UuidInterface $userId): array
    {
        return $this->entityManager->createQueryBuilder()
            ->from(StockItem::class, 'si')
            ->select('si, p, pos')
            ->join('si.product', 'p')
            ->join('si.position', 'pos')
            ->where('si.ean = :ean')
            ->setParameter('ean', $ean)
            ->andWhere('p.user = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array<StockItem>
     */
    public function getForProduct(UuidInterface $productId): array
    {
        return $this->entityManager->createQueryBuilder()
            ->from(StockItem::class, 'si')
            ->select('si')
            ->where('si.product = :productId')
            ->setParameter('productId', $productId)
            ->andWhere('si.quantity > 0')
            ->getQuery()
            ->setFetchMode(StockItem::class, 'position', ClassMetadata::FETCH_EAGER)
            ->getResult();
    }

    /**
     * @return array<StockItem>
     */
    public function getForLocation(UuidInterface $locationId): array
    {
        return $this->entityManager->createQueryBuilder()
            ->from(StockItem::class, 'si')
            ->select('si')
            ->join('si.position', 'pos')
            ->where('pos.location = :locationId')
            ->setParameter('locationId', $locationId)
            ->andWhere('si.quantity > 0')
            ->getQuery()
            ->setFetchMode(StockItem::class, 'product', ClassMetadata::FETCH_EAGER)
            ->setFetchMode(StockItem::class, 'position', ClassMetadata::FETCH_EAGER)
            ->getResult();
    }

    /**
     * @return array<StockItem>
     */
    public function getForPositionOfUser(UuidInterface $positionId, UuidInterface $userId): array
    {
        return $this->entityManager->createQueryBuilder()
            ->from(StockItem::class, 'si')
            ->select('si, p')
            ->join('si.position', 'pos')
            ->join('si.product', 'p')
            ->where('pos.id = :positionId')
            ->setParameter('positionId', $positionId)
            ->andWhere('p.user = :userId')
            ->setParameter('userId', $userId)
            ->andWhere('si.quantity > 0')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array<StockItem>
     */
    public function getForPosition(UuidInterface $positionId): array
    {
        return $this->entityManager->createQueryBuilder()
            ->from(StockItem::class, 'si')
            ->select('si')
            ->where('si.position = :positionId')
            ->setParameter('positionId', $positionId)
            ->andWhere('si.quantity > 0')
            ->getQuery()
            ->setFetchMode(StockItem::class, 'product', ClassMetadata::FETCH_EAGER)
            ->getResult();
    }

    /**
     * @return array<string, array<StockItem>>
     */
    public function getForOrder(UuidInterface $orderId): array
    {
        $connection = $this->entityManager->getConnection();

        $sql = <<<SQL
SELECT oi.product_id
FROM order_item oi
WHERE oi.order_id = :orderId
SQL;

        /** @var array<string> $products */
        $products = $connection->fetchFirstColumn($sql, ['orderId' => $orderId]);

        /** @var array<StockItem> $items */
        $items = $this->entityManager->createQueryBuilder()
            ->from(StockItem::class, 'si')
            ->select('si, p, l')
            ->join('si.position', 'p')
            ->join('p.location', 'l')
            ->where('si.product IN (:products)')
            ->setParameter('products', $products)
            ->getQuery()
            ->getResult();

        /** @var array<string, array<StockItem>> $positions */
        $positions = [];

        foreach ($items as $item) {
            $positions[$item->ean][] = $item;
        }

        return $positions;
    }

    /**
     * @return array<StockDemand>
     */
    public function getStockDemand(): array
    {
        $connection = $this->entityManager->getConnection();

        $sql = <<<SQL
SELECT
    order_item.sku,
    order_item.title,
    order_item.ean,
    COALESCE(SUM(stock_item.quantity), 0) AS stock_quantity,
    SUM(order_item.quantity - order_item.prepared_quantity) AS unpicked_ordered_quantity,
    COALESCE(SUM(stock_item.quantity), 0) - SUM(order_item.quantity - order_item.prepared_quantity) AS stock_difference
FROM order_item
LEFT JOIN stock_item ON order_item.product_id = stock_item.product_id
GROUP BY
    order_item.sku,
    order_item.title,
    order_item.ean
HAVING
    SUM(order_item.quantity - order_item.prepared_quantity) > 0
ORDER BY
    stock_difference ASC
SQL;

        /**
         * @var array<StockDemandData> $data
         */
        $data = $connection->fetchAllAssociative($sql);

        return array_map(
            callback: static fn(array $row) => StockDemand::fromArray($row),
            array: $data,
        );
    }

    /**
     * @return array<StockDemand>
     */
    public function getStockDemandForUser(UuidInterface $userId): array
    {
        $connection = $this->entityManager->getConnection();

        $sql = <<<SQL
SELECT
    order_item.sku,
    order_item.title,
    order_item.ean,
    COALESCE(SUM(stock_item.quantity), 0) AS stock_quantity,
    SUM(order_item.quantity - order_item.prepared_quantity) AS unpicked_ordered_quantity,
    COALESCE(SUM(stock_item.quantity), 0) - SUM(order_item.quantity - order_item.prepared_quantity) AS stock_difference
FROM order_item
INNER JOIN "order" ON "order".id = order_item.order_id 
LEFT JOIN stock_item ON order_item.product_id = stock_item.product_id
WHERE "order".user_id = :userId
GROUP BY
    order_item.sku,
    order_item.title,
    order_item.ean
HAVING
    SUM(order_item.quantity - order_item.prepared_quantity) > 0
ORDER BY
    stock_difference ASC
SQL;

        /**
         * @var array<StockDemandData> $data
         */
        $data = $connection->fetchAllAssociative($sql, [
            'userId' => $userId->toString(),
        ]);

        return array_map(
            callback: static fn(array $row) => StockDemand::fromArray($row),
            array: $data,
        );
    }
}
