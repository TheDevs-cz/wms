<?php

declare(strict_types=1);

namespace TheDevs\WMS\QueryResult;

readonly final class UserMonthlyAccounting
{
    public function __construct(
        public string $userId,
        public string $email,
        /** @var array<string, int> MM/YYYY => count */
        public array $data
    ) {}
}
