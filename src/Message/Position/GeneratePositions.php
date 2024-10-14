<?php

declare(strict_types=1);

namespace TheDevs\WMS\Message\Position;

use Ramsey\Uuid\UuidInterface;

readonly final class GeneratePositions
{
    public function __construct(
        public UuidInterface $locationId,
        public string $namePattern,
        public int $start,
        public int $end,
    ) {
    }
}
