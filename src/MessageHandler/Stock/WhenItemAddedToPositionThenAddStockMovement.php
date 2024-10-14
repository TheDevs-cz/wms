<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Stock;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Events\ItemAddedToPosition;

#[AsMessageHandler]
readonly final class WhenItemAddedToPositionThenAddStockMovement
{
    public function __invoke(ItemAddedToPosition $event): void
    {
    }
}
