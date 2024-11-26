<?php

declare(strict_types=1);

namespace TheDevs\WMS\Events;

use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

readonly final class ItemStockChanged
{
    public function __construct(
        public UuidInterface $stockItemId,
        public int $oldQuantity,
        public int $newQuantity,
        public UuidInterface $byUserId,
        public null|UuidInterface $orderId,
        public DateTimeImmutable $stockedAt,
    ) {
    }
}
