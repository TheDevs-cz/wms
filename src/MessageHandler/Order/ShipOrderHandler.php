<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Order;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Message\Order\ShipOrder;

#[AsMessageHandler]
readonly final class ShipOrderHandler
{
    public function __invoke(ShipOrder $message): void
    {

    }
}
