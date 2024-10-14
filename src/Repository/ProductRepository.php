<?php

declare(strict_types=1);

namespace TheDevs\WMS\Repository;

use Ramsey\Uuid\UuidInterface;
use TheDevs\WMS\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use TheDevs\WMS\Exceptions\ProductNotFound;

readonly final class ProductRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function add(Product $user): void
    {
        $this->entityManager->persist($user);
    }

    /**
     * @throws ProductNotFound
     */
    public function get(UuidInterface $id): Product
    {
        $user = $this->entityManager->find(Product::class, $id);

        if ($user instanceof Product) {
            return $user;
        }

        throw new ProductNotFound();
    }
}
