<?php

declare(strict_types=1);

namespace TheDevs\WMS\Message\Product;

use Ramsey\Uuid\UuidInterface;

readonly final class ProcessProductImport
{
    public function __construct(
        public UuidInterface $userId,
        public string $title,
        public string $sku,
        public string $ean,
        public null|string $category,
        public null|string $manufacturer,
        public null|string $image,
    ) {
    }
}
