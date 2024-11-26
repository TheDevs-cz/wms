<?php

declare(strict_types=1);

namespace TheDevs\WMS\Events;

use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;
use TheDevs\WMS\Value\OrderStatus;

readonly final class OrderStatusChanged
{
    public function __construct(
        public UuidInterface $orderId,
        public UuidInterface $userId,
        public OrderStatus $fromStatus,
        public OrderStatus $toStatus,
        public DateTimeImmutable $changedAt,
    ) {
    }
}
