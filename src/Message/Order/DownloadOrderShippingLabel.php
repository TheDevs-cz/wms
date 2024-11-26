<?php

declare(strict_types=1);

namespace TheDevs\WMS\Message\Order;

use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly final class DownloadOrderShippingLabel
{
    public function __construct(
        public UuidInterface $orderId,
        public int $packageCount,
    ) {
    }
}
