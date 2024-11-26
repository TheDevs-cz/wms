<?php

declare(strict_types=1);

namespace TheDevs\WMS\Exceptions;

use Symfony\Component\Messenger\Exception\UnrecoverableMessageHandlingException;

final class InsufficientStockItemQuantity extends UnrecoverableMessageHandlingException
{
}
