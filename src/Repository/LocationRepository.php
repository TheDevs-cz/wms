<?php

declare(strict_types=1);

namespace TheDevs\WMS\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\UuidInterface;
use TheDevs\WMS\Entity\Location;
use TheDevs\WMS\Exceptions\LocationNotFound;

readonly final class LocationRepository
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @throws LocationNotFound
     */
    public function get(UuidInterface $id): Location
    {
        $warehouse = $this->entityManager->find(Location::class, $id);

        if ($warehouse instanceof Location) {
            return $warehouse;
        }

        throw new LocationNotFound();
    }

    public function add(Location $warehouse): void
    {
        $this->entityManager->persist($warehouse);
    }
}
