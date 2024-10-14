<?php

declare(strict_types=1);

namespace TheDevs\WMS\Message\User;

readonly final class AddUser
{
    public function __construct(
        public string $email,
        public string $plainTextPassword,
        public null|string $name,
        public string $role,
    ) {
    }
}
