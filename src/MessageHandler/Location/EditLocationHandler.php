<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Location;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Exceptions\LocationNotFound;
use TheDevs\WMS\Exceptions\WarehouseNotFound;
use TheDevs\WMS\Message\Location\EditLocation;
use TheDevs\WMS\Repository\LocationRepository;
use TheDevs\WMS\Repository\WarehouseRepository;

#[AsMessageHandler]
readonly final class EditLocationHandler
{
    public function __construct(
        private LocationRepository $locationRepository,
        private WarehouseRepository $warehouseRepository,
    ) {
    }

    /**
     * @throws LocationNotFound
     * @throws WarehouseNotFound
     */
    public function __invoke(EditLocation $message): void
    {
        $location = $this->locationRepository->get($message->locationId);
        $warehouse = $this->warehouseRepository->get($message->warehouseId);

        $location->edit(
            $warehouse,
            $message->name,
        );
    }
}
