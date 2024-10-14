<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Location;

use Psr\Clock\ClockInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Entity\Location;
use TheDevs\WMS\Exceptions\WarehouseNotFound;
use TheDevs\WMS\Message\Location\GenerateLocations;
use TheDevs\WMS\Repository\LocationRepository;
use TheDevs\WMS\Repository\WarehouseRepository;
use TheDevs\WMS\Services\ProvideIdentity;

#[AsMessageHandler]
readonly final class GenerateLocationsHandler
{
    public function __construct(
        private LocationRepository $locationRepository,
        private WarehouseRepository $warehouseRepository,
        private ProvideIdentity $provideIdentity,
        private ClockInterface $clock,
    ) {
    }

    /**
     * @throws WarehouseNotFound
     */
    public function __invoke(GenerateLocations $message): void
    {
        $warehouse = $this->warehouseRepository->get($message->warehouseId);

        $start = $message->start;
        $end = $message->end;
        $namePattern = $message->namePattern;
        $paddingLength = strlen((string) $end);

        for ($i = $start; $i <= $end; $i++) {
            $paddedNumber = str_pad((string) $i, $paddingLength, '0', STR_PAD_LEFT);
            $name = str_replace('{cislo}', $paddedNumber, $namePattern);

            $location = new Location(
                $this->provideIdentity->next(),
                $warehouse,
                $this->clock->now(),
                $name
            );

            $this->locationRepository->add($location);
        }
    }
}
