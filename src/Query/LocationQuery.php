<?php

declare(strict_types=1);

namespace TheDevs\WMS\Query;

use Doctrine\ORM\EntityManagerInterface;
use TheDevs\WMS\Entity\Location;

readonly final class LocationQuery
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * @return array<Location>
     */
    public function getAll(): array
    {
        return $this->entityManager->createQueryBuilder()
            ->from(Location::class, 'l')
            ->select('l')
            ->getQuery()
            ->getResult();
    }
}
