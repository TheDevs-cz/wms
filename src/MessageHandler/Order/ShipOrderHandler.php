<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Order;

use Psr\Clock\ClockInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Message\Order\ReportOrderProblem;
use TheDevs\WMS\Message\Order\ShipOrder;
use TheDevs\WMS\Repository\OrderRepository;
use TheDevs\WMS\Repository\UserRepository;

#[AsMessageHandler]
readonly final class ShipOrderHandler
{
    public function __construct(
        private OrderRepository $orderRepository,
        private UserRepository $userRepository,
        private ClockInterface $clock,
    ) {
    }

    public function __invoke(ShipOrder $message): void
    {
        $user = $this->userRepository->getById($message->userId);
        $order = $this->orderRepository->get($message->orderId);

        $order->ship(
            $user->id,
            $this->clock->now(),
        );
    }
}
