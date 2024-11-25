<?php

declare(strict_types=1);

namespace TheDevs\WMS\Value;

enum OrderStatus: string
{
    case Open = 'open';
    case Picking = 'picking';
    case Packing = 'packing';
    case Problem = 'problem';
    case Shipped = 'shipped';
    case Completed = 'completed';
    case Cancelled = 'cancelled';
}
