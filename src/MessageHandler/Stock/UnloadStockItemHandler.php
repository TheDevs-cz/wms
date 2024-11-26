<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Stock;

use Psr\Clock\ClockInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Exceptions\InsufficientStockItemQuantity;
use TheDevs\WMS\Exceptions\StockItemNotFound;
use TheDevs\WMS\Message\Stock\UnloadStockItem;
use TheDevs\WMS\Query\StockItemQuery;

#[AsMessageHandler]
readonly final class UnloadStockItemHandler
{
    public function __construct(
        private ClockInterface $clock,
        private StockItemQuery $stockItemQuery,
    ) {
    }

    /**
     * @throws StockItemNotFound
     * @throws InsufficientStockItemQuantity
     */
    public function __invoke(UnloadStockItem $message): void
    {
        $now = $this->clock->now();

        $stockItem = $this->stockItemQuery->getByPositionAndEan($message->positionId, $message->ean);
        $stockItem->unload($message->userId, $message->quantity, null, $now);
    }
}
