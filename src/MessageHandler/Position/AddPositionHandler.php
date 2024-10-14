<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Position;

use Psr\Clock\ClockInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Entity\Position;
use TheDevs\WMS\Exceptions\LocationNotFound;
use TheDevs\WMS\Message\Position\AddPosition;
use TheDevs\WMS\Repository\LocationRepository;
use TheDevs\WMS\Repository\PositionRepository;
use TheDevs\WMS\Services\ProvideIdentity;

#[AsMessageHandler]
readonly final class AddPositionHandler
{
    public function __construct(
        private PositionRepository $positionRepository,
        private ClockInterface $clock,
        private ProvideIdentity $provideIdentity,
        private LocationRepository $locationRepository,
    ) {
    }

    /**
     * @throws LocationNotFound
     */
    public function __invoke(AddPosition $message): void
    {
        $location = $this->locationRepository->get($message->locationId);

        $position = new Position(
            $this->provideIdentity->next(),
            $location,
            $this->clock->now(),
            $message->name,
        );

        $this->positionRepository->add($position);
    }
}
