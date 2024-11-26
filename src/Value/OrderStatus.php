<?php

declare(strict_types=1);

namespace TheDevs\WMS\Value;

enum OrderStatus: string
{
    case Open = 'open';
    case Picking = 'picking';
    case Completed = 'completed';
    case Packing = 'packing';
    case Shipped = 'shipped';
    case Problem = 'problem';
    case Cancelled = 'cancelled';
}
