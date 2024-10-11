<?php

declare(strict_types=1);

namespace TheDevs\WMS\Message\User;

readonly final class ResetPassword
{
    public function __construct(
        public string $token,
        public string $newPlainTextPassword,
    ) {
    }
}
