<?php

declare(strict_types=1);

namespace TheDevs\WMS\Message\Product;

use Ramsey\Uuid\UuidInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

readonly final class ImportProducts
{
    public function __construct(
        public UuidInterface $userId,
        public UploadedFile $feed,
    ) {
    }
}
