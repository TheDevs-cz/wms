<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Order;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Entity\Order;
use TheDevs\WMS\Entity\OrderItem;
use TheDevs\WMS\Exceptions\ProductNotFound;
use TheDevs\WMS\Exceptions\UserNotFound;
use TheDevs\WMS\Message\Order\CreateOrder;
use TheDevs\WMS\Query\ProductQuery;
use TheDevs\WMS\Repository\OrderItemRepository;
use TheDevs\WMS\Repository\OrderRepository;
use TheDevs\WMS\Repository\UserRepository;
use TheDevs\WMS\Services\ProvideIdentity;

#[AsMessageHandler]
readonly final class CreateOrderHandler
{
    public function __construct(
        private OrderRepository $orderRepository,
        private OrderItemRepository $orderItemRepository,
        private ProvideIdentity $provideIdentity,
        private UserRepository $userRepository,
        private ProductQuery $productQuery,
    )
    {
    }

    /**
     * @throws UserNotFound
     */
    public function __invoke(CreateOrder $message): void
    {
        $user = $this->userRepository->getById($message->userId);

        $order = new Order(
            $message->id,
            $user,
            orderedAt: $message->orderedAt,
            number: $message->number,
            price: $message->price,
            cashOnDelivery: $message->cashOnDelivery,
            paymentPrice: $message->paymentPrice,
            deliveryPrice: $message->deliveryPrice,
            carrier: $message->carrier,
            deliveryAddress: $message->deliveryAddress,
        );

        $this->orderRepository->add($order);

        foreach ($message->items as $item) {
            try {
                $product = $this->productQuery->getByEanForUser($user->id, $item->ean);
            } catch (ProductNotFound) {
                $product = null;
            }

            $orderItem = new OrderItem(
                $this->provideIdentity->next(),
                $order,
                $product,
                title: $item->title,
                quantity: $item->quantity,
                ean: $item->ean,
                itemPrice: $item->itemPrice,
                sku: $item->sku,
                serialNumbers: $item->serialNumbers,
            );

            $this->orderItemRepository->add($orderItem);
        }
    }
}
