<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Stock;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Events\ItemStockChanged;

#[AsMessageHandler]
readonly final class WhenItemStockChangedThenAddStockMovement
{
    public function __invoke(ItemStockChanged $event): void
    {
    }
}
