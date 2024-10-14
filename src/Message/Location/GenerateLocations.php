<?php

declare(strict_types=1);

namespace TheDevs\WMS\Message\Location;

use Ramsey\Uuid\UuidInterface;

readonly final class GenerateLocations
{
    public function __construct(
        public UuidInterface $warehouseId,
        public string $namePattern,
        public int $start,
        public int $end,
    ) {
    }
}
