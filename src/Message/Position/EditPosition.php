<?php

declare(strict_types=1);

namespace TheDevs\WMS\Message\Position;

use Ramsey\Uuid\UuidInterface;

readonly final class EditPosition
{
    public function __construct(
        public UuidInterface $positionId,
        public UuidInterface $locationId,
        public string $title,
    ) {
    }
}
