<?php

declare(strict_types=1);

namespace TheDevs\WMS\Message\Position;

use Ramsey\Uuid\UuidInterface;

readonly final class AddPosition
{
    public function __construct(
        public UuidInterface $locationId,
        public string $name,
    ) {
    }
}
