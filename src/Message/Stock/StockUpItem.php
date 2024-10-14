<?php

declare(strict_types=1);

namespace TheDevs\WMS\Message\Stock;

use Ramsey\Uuid\UuidInterface;

readonly final class StockUpItem
{
    public function __construct(
        public UuidInterface $userId,
        public UuidInterface $positionId,
        public string $ean,
        public int $quantity,
    ) {
    }
}
