<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Order;

use Psr\Clock\ClockInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Entity\OrderHistory;
use TheDevs\WMS\Events\OrderReceived;
use TheDevs\WMS\Exceptions\OrderNotFound;
use TheDevs\WMS\Repository\OrderHistoryRepository;
use TheDevs\WMS\Repository\OrderRepository;
use TheDevs\WMS\Services\ProvideIdentity;
use TheDevs\WMS\Value\OrderStatus;

#[AsMessageHandler]
readonly final class WhenOrderReceivedThenAddOrderHistoryHandler
{
    public function __construct(
        private ProvideIdentity $provideIdentity,
        private ClockInterface $clock,
        private OrderRepository $orderRepository,
        private OrderHistoryRepository $orderHistoryRepository,
    ) {
    }

    /**
     * @throws OrderNotFound
     */
    public function __invoke(OrderReceived $event): void
    {
        $order = $this->orderRepository->get($event->orderId);

        $history = new OrderHistory(
            $this->provideIdentity->next(),
            $order,
            $order->user,
            $this->clock->now(),
            null,
            OrderStatus::Open,
        );

        $this->orderHistoryRepository->add($history);
    }
}
