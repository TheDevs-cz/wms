<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Order;

use Psr\Clock\ClockInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Entity\OrderHistory;
use TheDevs\WMS\Events\OrderStatusChanged;
use TheDevs\WMS\Exceptions\OrderNotFound;
use TheDevs\WMS\Repository\OrderHistoryRepository;
use TheDevs\WMS\Repository\OrderRepository;
use TheDevs\WMS\Repository\UserRepository;
use TheDevs\WMS\Services\ProvideIdentity;

#[AsMessageHandler]
readonly final class WhenOrderStatusChangedThenAddOrderHistoryHandler
{
    public function __construct(
        private ProvideIdentity $provideIdentity,
        private ClockInterface $clock,
        private OrderRepository $orderRepository,
        private OrderHistoryRepository $orderHistoryRepository,
        private UserRepository $userRepository,
    ) {
    }

    /**
     * @throws OrderNotFound
     */
    public function __invoke(OrderStatusChanged $event): void
    {
        $order = $this->orderRepository->get($event->orderId);
        $author = $this->userRepository->getById($event->userId);

        $history = new OrderHistory(
            $this->provideIdentity->next(),
            $order,
            $author,
            $this->clock->now(),
            $event->fromStatus,
            $event->toStatus,
        );

        $this->orderHistoryRepository->add($history);
    }
}
