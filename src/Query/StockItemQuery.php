<?php

declare(strict_types=1);

namespace TheDevs\WMS\Query;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\NoResultException;
use Ramsey\Uuid\UuidInterface;
use TheDevs\WMS\Entity\StockItem;
use TheDevs\WMS\Exceptions\StockItemNotFound;

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
     * @return array<StockItem>
     */
    public function getForProduct(UuidInterface $productId): array
    {
        return $this->entityManager->createQueryBuilder()
            ->from(StockItem::class, 'si')
            ->select('si')
            ->where('si.product = :productId')
            ->setParameter('productId', $productId)
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
            ->getQuery()
            ->setFetchMode(StockItem::class, 'product', ClassMetadata::FETCH_EAGER)
            ->setFetchMode(StockItem::class, 'position', ClassMetadata::FETCH_EAGER)
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
            ->getQuery()
            ->setFetchMode(StockItem::class, 'product', ClassMetadata::FETCH_EAGER)
            ->getResult();
    }
}
