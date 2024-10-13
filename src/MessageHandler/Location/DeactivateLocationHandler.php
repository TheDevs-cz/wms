<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Location;

use Psr\Clock\ClockInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Exceptions\LocationNotFound;
use TheDevs\WMS\Message\Location\DeactivateLocation;
use TheDevs\WMS\Repository\LocationRepository;

#[AsMessageHandler]
readonly final class DeactivateLocationHandler
{
    public function __construct(
        private LocationRepository $locationRepository,
        private ClockInterface $clock,
    ) {
    }

    /**
     * @throws LocationNotFound
     */
    public function __invoke(DeactivateLocation $message): void
    {
        $location = $this->locationRepository->get($message->locationId);

        $location->deactivate($this->clock->now());
    }
}
