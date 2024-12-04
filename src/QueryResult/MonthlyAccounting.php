<?php

declare(strict_types=1);

namespace TheDevs\WMS\QueryResult;

readonly final class MonthlyAccounting
{
    /**
     * @param array<string> $months MM/YYYY
     * @param array<UserMonthlyAccounting> $data
     */
    public function __construct(
        public array $months,
        public array $data
    ) {}

    /**
     * @param array<array{
     *     user_id: string,
     *     email: string,
     *     year: int,
     *     month: int,
     *     shipped_count: int,
     * }> $rows
     * @return MonthlyAccounting
     */
    public static function fromDatabaseResults(array $rows): self
    {
        $months = [];
        $userData = [];
        $userTempData = [];

        foreach ($rows as $row) {
            $userId = $row['user_id'];
            $yearMonth = "{$row['month']}/{$row['year']}";
            $months[$yearMonth] = true;

            // Initialize user data if not already present
            if (!isset($userTempData[$userId])) {
                $userTempData[$userId] = [
                    'email' => $row['email'],
                    'data' => [],
                ];
            }

            // Add shipped count for the specific month
            $userTempData[$userId]['data'][$yearMonth] = $row['shipped_count'];
        }

        $sortedMonths = array_keys($months);
        rsort($sortedMonths);

        foreach ($userTempData as $userId => $user) {
            $userData[] = new UserMonthlyAccounting(
                $userId,
                $user['email'],
                $user['data'],
            );
        }

        return new self($sortedMonths, $userData);
    }
}
