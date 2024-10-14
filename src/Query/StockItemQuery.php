<?php

declare(strict_types=1);

namespace TheDevs\WMS\Query;

use Doctrine\ORM\EntityManagerInterface;
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
}
