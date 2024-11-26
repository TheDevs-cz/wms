<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Order;

use Psr\Clock\ClockInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Message\Order\ReportOrderProblem;
use TheDevs\WMS\Repository\OrderRepository;
use TheDevs\WMS\Repository\UserRepository;

#[AsMessageHandler]
readonly final class ReportOrderProblemHandler
{
    public function __construct(
        private OrderRepository $orderRepository,
        private UserRepository $userRepository,
        private ClockInterface $clock,
    ) {
    }

    public function __invoke(ReportOrderProblem $message): void
    {
        $user = $this->userRepository->getById($message->userId);
        $order = $this->orderRepository->get($message->orderId);

        $order->markAsProblematic(
            $user->id,
            $this->clock->now(),
        );
    }
}
