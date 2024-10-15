<?php

declare(strict_types=1);

namespace TheDevs\WMS\Events;

use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

readonly final class ItemAddedToPosition
{
    public function __construct(
        public string $ean,
        public UuidInterface $positionId,
        public int $quantity,
        public UuidInterface $byUserId,
        public DateTimeImmutable $addedAt,
    ) {
    }
}
