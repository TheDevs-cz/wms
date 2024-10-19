<?php

declare(strict_types=1);

namespace TheDevs\WMS\Value;

enum OrderStatus: string
{
    case Open = 'open';
    case Completed = 'completed';
    case Shipped = 'shipped';
    case Cancelled = 'cancelled';
}
