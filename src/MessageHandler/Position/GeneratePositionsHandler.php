<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Position;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Message\Position\GeneratePositions;

#[AsMessageHandler]
readonly final class GeneratePositionsHandler
{
    public function __invoke(GeneratePositions $message): void
    {
    }
}
