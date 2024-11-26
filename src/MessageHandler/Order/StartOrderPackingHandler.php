<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Order;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Message\Order\StartOrderPacking;

#[AsMessageHandler]
readonly final class StartOrderPackingHandler
{
    public function __invoke(StartOrderPacking $message): void
    {

    }
}
