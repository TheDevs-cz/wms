<?php

declare(strict_types=1);

namespace TheDevs\WMS\Events;

use Ramsey\Uuid\UuidInterface;

readonly final class OrderReceived
{
    public function __construct(
        public UuidInterface $orderId,
    ) {
    }
}
