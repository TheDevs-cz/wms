<?php

declare(strict_types=1);

namespace TheDevs\WMS\Message\Location;

use Ramsey\Uuid\UuidInterface;

readonly final class AddLocation
{
    public function __construct(
        public UuidInterface $warehouseId,
        public string $name,
    ) {
    }
}
