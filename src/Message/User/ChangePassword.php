<?php

declare(strict_types=1);

namespace TheDevs\WMS\Message\User;

readonly final class ChangePassword
{
    public function __construct(
        public string $userEmail,
        public string $newPlainTextPassword,
    ) {
    }
}
