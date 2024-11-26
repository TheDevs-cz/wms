<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Order;

use Psr\Clock\ClockInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Exceptions\InsufficientStockItemQuantity;
use TheDevs\WMS\Exceptions\MultipleOrderItemsFoundOnPosition;
use TheDevs\WMS\Exceptions\MultipleStockItemsFound;
use TheDevs\WMS\Exceptions\NoOrderItemFoundOnPosition;
use TheDevs\WMS\Exceptions\OrderItemAlreadyFullyPrepared;
use TheDevs\WMS\Exceptions\OrderItemNotFound;
use TheDevs\WMS\Exceptions\OrderNotFound;
use TheDevs\WMS\Exceptions\StockItemNotFound;
use TheDevs\WMS\Exceptions\UserNotFound;
use TheDevs\WMS\Message\Order\PrepareOrderItem;
use TheDevs\WMS\Query\StockItemQuery;
use TheDevs\WMS\Repository\OrderRepository;
use TheDevs\WMS\Repository\UserRepository;

#[AsMessageHandler]
readonly final class PrepareOrderItemHandler
{
    public function __construct(
        private OrderRepository $orderRepository,
        private UserRepository $userRepository,
        private StockItemQuery $stockItemQuery,
        private ClockInterface $clock,
    ) {
    }

    /**
     * @throws OrderNotFound
     * @throws UserNotFound
     * @throws OrderItemNotFound
     * @throws OrderItemAlreadyFullyPrepared
     * @throws StockItemNotFound
     * @throws NoOrderItemFoundOnPosition
     * @throws MultipleStockItemsFound
     * @throws InsufficientStockItemQuantity
     */
    public function __invoke(PrepareOrderItem $message): void
    {
        $order = $this->orderRepository->get($message->orderId);
        $user = $this->userRepository->getById($message->userId);
        $stockItem = null;

        if ($message->ean !== null && $message->positionId === null) {
            $stockItem = $this->stockItemQuery->getByEanOfUser($message->ean, $order->user->id);
        }

        if ($message->positionId !== null && $message->ean === null) {
            $stockItems = $this->stockItemQuery->getForPositionOfUser($message->positionId, $order->user->id);

            if (count($stockItems) === 0) {
                throw new NoOrderItemFoundOnPosition();
            }

            if (count($stockItems) > 1) {
                throw new MultipleOrderItemsFoundOnPosition();
            }

            $stockItem = $stockItems[array_key_first($stockItems)];
        }

        if ($message->ean !== null && $message->positionId !== null) {
            $stockItem = $this->stockItemQuery->getByPositionAndEanOfUser($message->positionId, $message->ean, $order->user->id);
        }

        if ($stockItem === null) {
            throw new StockItemNotFound();
        }

        $order->pickItem(
            $stockItem,
            $message->userId,
            1,
            $this->clock->now(),
        );
    }
}
