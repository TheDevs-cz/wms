<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Location;

use Psr\Clock\ClockInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Entity\Location;
use TheDevs\WMS\Exceptions\WarehouseNotFound;
use TheDevs\WMS\Message\Location\AddLocation;
use TheDevs\WMS\Repository\LocationRepository;
use TheDevs\WMS\Repository\WarehouseRepository;
use TheDevs\WMS\Services\ProvideIdentity;

#[AsMessageHandler]
readonly final class AddLocationHandler
{
    public function __construct(
        private LocationRepository $locationRepository,
        private ClockInterface $clock,
        private ProvideIdentity $provideIdentity,
        private WarehouseRepository $warehouseRepository,
    ) {
    }

    /**
     * @throws WarehouseNotFound
     */
    public function __invoke(AddLocation $message): void
    {
        $warehouse = $this->warehouseRepository->get($message->warehouseId);

        $location = new Location(
            $this->provideIdentity->next(),
            $warehouse,
            $this->clock->now(),
            $message->name,
        );

        $this->locationRepository->add($location);
    }
}
