<?php

declare(strict_types=1);

namespace TheDevs\WMS\Value;

enum LabelType: string
{
    case Small = '90x25';
    case Big = '100x150';

    public function getWidth(): string
    {
        return match ($this) {
            self::Small => '90mm',
            self::Big => '100mm',
        };
    }

    public function getHeight(): string
    {
        return match ($this) {
            self::Small => '25mm',
            self::Big => '150mm',
        };
    }
}
