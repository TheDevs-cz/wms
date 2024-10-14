<?php

declare(strict_types=1);

namespace TheDevs\WMS\Query;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NoResultException;
use Ramsey\Uuid\UuidInterface;
use TheDevs\WMS\Entity\Product;
use TheDevs\WMS\Exceptions\ProductNotFound;

readonly final class ProductQuery
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @return array<Product>
     */
    public function getAll(): array
    {
        return $this->entityManager->createQueryBuilder()
            ->from(Product::class, 'p')
            ->select('p')
            ->getQuery()
            ->getResult();
    }

    /**
     * @throws ProductNotFound
     */
    public function searchByEan(UuidInterface $userId, string $ean): Product
    {
        try {
            /** @var Product $row */
            $row = $this->entityManager->createQueryBuilder()
                ->from(Product::class, 'p')
                ->select('p')
                ->where('p.ean = :ean')
                ->setParameter('ean', $ean)
                ->andWhere('p.user = :userId')
                ->setParameter('userId', $userId)
                ->getQuery()
                ->getSingleResult();

            return $row;
        } catch (NoResultException $e) {
            throw new ProductNotFound(previous: $e);
        }
    }
}
