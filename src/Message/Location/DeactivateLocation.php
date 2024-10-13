<?php

declare(strict_types=1);

namespace TheDevs\WMS\Message\Location;

use Ramsey\Uuid\UuidInterface;

readonly final class DeactivateLocation
{
    public function __construct(
        public UuidInterface $locationId,
    ) {
    }
}
