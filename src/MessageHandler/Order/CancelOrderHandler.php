<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Order;

use Psr\Clock\ClockInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Message\Order\CancelOrder;
use TheDevs\WMS\Repository\OrderRepository;
use TheDevs\WMS\Repository\UserRepository;

#[AsMessageHandler]
readonly final class CancelOrderHandler
{
    public function __construct(
        private OrderRepository $orderRepository,
        private UserRepository $userRepository,
        private ClockInterface $clock,
        private Security $security,
    ) {
    }

    public function __invoke(CancelOrder $message): void
    {
        $orderId = Uuid::fromString($message->orderId);
        $order = $this->orderRepository->get($orderId);

        $email = $this->security->getUser()?->getUserIdentifier() ?? '';
        $user = $this->userRepository->get($email);

        $order->cancel(
            $user->id,
            $this->clock->now(),
        );
    }
}
