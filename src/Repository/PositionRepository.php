<?php

declare(strict_types=1);

namespace TheDevs\WMS\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use TheDevs\WMS\Entity\Position;
use TheDevs\WMS\Exceptions\PositionNotFound;

readonly final class PositionRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @throws PositionNotFound
     */
    public function get(UuidInterface $id): Position
    {
        $warehouse = $this->entityManager->find(Position::class, $id);

        if ($warehouse instanceof Position) {
            return $warehouse;
        }

        throw new PositionNotFound();
    }

    public function add(Position $warehouse): void
    {
        $this->entityManager->persist($warehouse);
    }
}
