<?php

declare(strict_types=1);

namespace TheDevs\WMS\Message\Order;

use Ramsey\Uuid\UuidInterface;

readonly final class PrepareOrderItem
{
    public function __construct(
        public UuidInterface $userId,
        public UuidInterface $orderId,
        public null|string $ean,
        public null|UuidInterface $positionId,
    ) {
    }
}
