<?php

declare(strict_types=1);

namespace TheDevs\WMS\Value;

enum OrdersFilter: string
{
    case Unfinished = 'unfinished';
    case Finished = 'finished';
    case All = 'all';

    public function text(): string
    {
        return match ($this) {
            self::Unfinished => 'Nedokončené',
            self::Finished => 'Dokončené',
            self::All => 'Všechny',
        };
    }
}
