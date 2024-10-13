<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Position;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Exceptions\LocationNotFound;
use TheDevs\WMS\Exceptions\PositionNotFound;
use TheDevs\WMS\Message\Position\EditPosition;
use TheDevs\WMS\Repository\LocationRepository;
use TheDevs\WMS\Repository\PositionRepository;

#[AsMessageHandler]
readonly final class EditPositionHandler
{
    public function __construct(
        private PositionRepository $positionRepository,
        private LocationRepository $locationRepository,
    ) {
    }

    /**
     * @throws PositionNotFound
     * @throws LocationNotFound
     */
    public function __invoke(EditPosition $message): void
    {
        $position = $this->positionRepository->get($message->positionId);
        $location = $this->locationRepository->get($message->locationId);

        $position->edit(
            $location,
            $message->title,
        );
    }
}
