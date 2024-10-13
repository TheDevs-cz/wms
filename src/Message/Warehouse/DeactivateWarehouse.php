<?php

declare(strict_types=1);

namespace TheDevs\WMS\Message\Warehouse;

use Ramsey\Uuid\UuidInterface;

readonly final class DeactivateWarehouse
{
    public function __construct(
        public UuidInterface $warehouseId,
    ) {
    }
}
