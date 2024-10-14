<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Position;

use Psr\Clock\ClockInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Entity\Position;
use TheDevs\WMS\Exceptions\LocationNotFound;
use TheDevs\WMS\Message\Position\GeneratePositions;
use TheDevs\WMS\Repository\LocationRepository;
use TheDevs\WMS\Repository\PositionRepository;
use TheDevs\WMS\Services\ProvideIdentity;

#[AsMessageHandler]
readonly final class GeneratePositionsHandler
{
    public function __construct(
        private LocationRepository $locationRepository,
        private PositionRepository $positionRepository,
        private ProvideIdentity $provideIdentity,
        private ClockInterface $clock,
    ) {
    }

    /**
     * @throws LocationNotFound
     */
    public function __invoke(GeneratePositions $message): void
    {
        $location = $this->locationRepository->get($message->locationId);

        $start = $message->start;
        $end = $message->end;
        $namePattern = $message->namePattern;
        $paddingLength = strlen((string) $end);

        for ($i = $start; $i <= $end; $i++) {
            $paddedNumber = str_pad((string) $i, $paddingLength, '0', STR_PAD_LEFT);
            $name = str_replace('{cislo}', $paddedNumber, $namePattern);

            $position = new Position(
                $this->provideIdentity->next(),
                $location,
                $this->clock->now(),
                $name
            );

            $this->positionRepository->add($position);
        }
    }
}
