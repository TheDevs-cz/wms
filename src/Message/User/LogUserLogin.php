<?php

declare(strict_types=1);

namespace TheDevs\WMS\Message\User;

use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;

readonly final class LogUserLogin
{
    public function __construct(
        public UuidInterface $userId,
        public DateTimeImmutable $time,
    ) {
    }
}
