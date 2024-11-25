<?php

declare(strict_types=1);

namespace TheDevs\WMS\Services;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

readonly final class ExtractUuid
{
    public static function fromUrl(string $url): null|UuidInterface
    {
        $uuidPattern = '/[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}/i';

        if (preg_match($uuidPattern, $url, $matches)) {
            return Uuid::fromString($matches[0]);
        }

        return null;
    }
}
