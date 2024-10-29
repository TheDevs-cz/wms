<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Order;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Entity\StockItem;
use TheDevs\WMS\Exceptions\OrderItemAlreadyFullyPrepared;
use TheDevs\WMS\Exceptions\OrderItemNotFound;
use TheDevs\WMS\Exceptions\OrderNotFound;
use TheDevs\WMS\Exceptions\StockItemNotFound;
use TheDevs\WMS\Exceptions\UserNotFound;
use TheDevs\WMS\Message\Order\PrepareOrderItem;
use TheDevs\WMS\Query\OrderItemQuery;
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
        private OrderItemQuery $orderItemQuery,
    )
    {
    }

    /**
     * @throws OrderNotFound
     * @throws UserNotFound
     * @throws OrderItemNotFound
     * @throws OrderItemAlreadyFullyPrepared
     */
    public function __invoke(PrepareOrderItem $message): void
    {
        $order = $this->orderRepository->get($message->orderId);
        $user = $this->userRepository->getById($message->userId);
        $stockItem = null;

        if ($message->positionId === null && $message->ean === null) {
            throw new \Exception('nemuze chybet oboji');
        }

        // 1) identify stockItem
        if ($message->ean !== null && $message->positionId === null) {
            // ean ma vice pozic - nelze identifikovat pozici
            $stockItem = $this->stockItemQuery->getByEanOfUser($message->ean, $user->id);
        }

        if ($message->positionId !== null && $message->ean === null) {
            // na pozici je vice itemu z objednavky - nelze identifikovat ean
            $stockItems = $this->stockItemQuery->getForPositionOfUser($message->positionId, $user->id);

            throw new StockItemNotFound();

            // if (count($stockItems) === 0) {}
            // if (count($stockItems) > 0) {}
            // $stockItem = $stockItems[array_key_first($stockItems)];
        }

        if ($message->ean !== null && $message->positionId !== null) {
            $stockItem = $this->stockItemQuery->getByPositionAndEanOfUser($message->positionId, $message->ean, $user->id);
        }

        assert($stockItem instanceof StockItem);

        // 2) najit orderItem ke stock itemu
        $orderItem = $this->orderItemQuery->getByEanForOrder($stockItem->ean, $message->orderId);

        if ($stockItem->quantity <= 0) {
            throw new \Exception('nedostatek skladovych zasob');
        }

        $orderItem->prepareForExpedition(
            $message->userId,
            $stockItem->position->id,
            1,
        );
    }
}
