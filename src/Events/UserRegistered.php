<?php

declare(strict_types=1);

namespace TheDevs\WMS\Events;

readonly final class UserRegistered
{
    public function __construct(
        public string $email,
    ) {
    }
}
