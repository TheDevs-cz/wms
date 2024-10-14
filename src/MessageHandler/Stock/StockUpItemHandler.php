<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Stock;

use Psr\Clock\ClockInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Entity\StockItem;
use TheDevs\WMS\Exceptions\PositionNotFound;
use TheDevs\WMS\Exceptions\ProductNotFound;
use TheDevs\WMS\Exceptions\StockItemNotFound;
use TheDevs\WMS\Message\Stock\StockUpItem;
use TheDevs\WMS\Query\ProductQuery;
use TheDevs\WMS\Query\StockItemQuery;
use TheDevs\WMS\Repository\PositionRepository;
use TheDevs\WMS\Repository\StockItemRepository;
use TheDevs\WMS\Services\ProvideIdentity;

#[AsMessageHandler]
readonly final class StockUpItemHandler
{
    public function __construct(
        private StockItemRepository $stockItemRepository,
        private ProductQuery $productQuery,
        private PositionRepository $positionRepository,
        private ProvideIdentity $provideIdentity,
        private ClockInterface $clock,
        private StockItemQuery $stockItemQuery,
    ) {
    }

    /**
     * @throws PositionNotFound
     */
    public function __invoke(StockUpItem $message): void
    {
        $position = $this->positionRepository->get($message->positionId);

        try {
            $product = $this->productQuery->getByEan($message->ean);
        } catch (ProductNotFound) {
            $product = null;
        }

        $now = $this->clock->now();

        try {
            $stockItem = $this->stockItemQuery->getByPositionAndEan($message->positionId, $message->ean);
            $stockItem->stockUp($message->userId, $message->quantity, $now);
        } catch (StockItemNotFound) {
            $stockItem = new StockItem(
                $this->provideIdentity->next(),
                $product,
                $position,
                $now,
                null,
                $message->ean,
                $message->quantity,
                $message->userId,
            );

            $this->stockItemRepository->add($stockItem);
        }
    }
}
