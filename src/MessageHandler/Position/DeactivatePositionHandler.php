<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Position;

use Psr\Clock\ClockInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Exceptions\PositionNotFound;
use TheDevs\WMS\Message\Position\DeactivatePosition;
use TheDevs\WMS\Repository\PositionRepository;

#[AsMessageHandler]
readonly final class DeactivatePositionHandler
{
    public function __construct(
        private PositionRepository $positionRepository,
        private ClockInterface $clock,
    ) {
    }

    /**
     * @throws PositionNotFound
     */
    public function __invoke(DeactivatePosition $message): void
    {
        $position = $this->positionRepository->get($message->positionId);

        $position->deactivate($this->clock->now());
    }
}
