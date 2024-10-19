<?php

declare(strict_types=1);

namespace TheDevs\WMS\Value;

enum Currency: string
{
    case CZK = 'czk';
    case EUR = 'eur';
    case PLN = 'pln';
}
