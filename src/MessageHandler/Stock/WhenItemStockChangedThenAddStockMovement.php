<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Stock;

use Psr\Clock\ClockInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Entity\StockMovement;
use TheDevs\WMS\Events\ItemStockChanged;
use TheDevs\WMS\Exceptions\StockItemNotFound;
use TheDevs\WMS\Exceptions\UserNotFound;
use TheDevs\WMS\Repository\OrderRepository;
use TheDevs\WMS\Repository\StockItemRepository;
use TheDevs\WMS\Repository\StockMovementRepository;
use TheDevs\WMS\Repository\UserRepository;
use TheDevs\WMS\Services\ProvideIdentity;

#[AsMessageHandler]
readonly final class WhenItemStockChangedThenAddStockMovement
{
    public function __construct(
        private ProvideIdentity $provideIdentity,
        private ClockInterface $clock,
        private UserRepository $userRepository,
        private StockMovementRepository $stockMovementRepository,
        private StockItemRepository $stockItemRepository,
        private OrderRepository $orderRepository,
    ) {
    }

    /**
     * @throws UserNotFound
     * @throws StockItemNotFound
     */
    public function __invoke(ItemStockChanged $event): void
    {
        $user = $this->userRepository->getById($event->byUserId);
        $stockItem = $this->stockItemRepository->get($event->stockItemId);

        $fromPosition = $stockItem->position;
        $toPosition = $stockItem->position;

        // Adding to a position or removing from a position ...
        if ($event->newQuantity > $event->oldQuantity) {
            $fromPosition = null;
        }  else {
            $toPosition = null;
        }

        $order = null;

        if ($event->orderId !== null) {
            $this->orderRepository->get($event->orderId);
        }

        $movement = new StockMovement(
            $this->provideIdentity->next(),
            $user,
            $stockItem->ean,
            $stockItem->sku,
            $event->oldQuantity,
            $event->newQuantity,
            $stockItem->product,
            $fromPosition,
            $toPosition,
            $order,
            $this->clock->now(),
        );

        $this->stockMovementRepository->add($movement);
    }
}
