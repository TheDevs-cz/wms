<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Location;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Message\Location\GenerateLocations;

#[AsMessageHandler]
readonly final class GenerateLocationsHandler
{
    public function __invoke(GenerateLocations $message): void
    {
    }
}
