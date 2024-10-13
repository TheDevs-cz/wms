<?php

declare(strict_types=1);

namespace TheDevs\WMS\Message\Location;

use Ramsey\Uuid\UuidInterface;

readonly final class EditLocation
{
    public function __construct(
        public UuidInterface $locationId,
        public UuidInterface $warehouseId,
        public string $name,
    ) {
    }
}
