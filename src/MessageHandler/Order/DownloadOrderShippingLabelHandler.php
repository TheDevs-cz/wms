<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Order;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Exceptions\OrderNotFound;
use TheDevs\WMS\Message\Order\DownloadOrderShippingLabel;
use TheDevs\WMS\Repository\OrderRepository;

#[AsMessageHandler]
readonly final class DownloadOrderShippingLabelHandler
{
    public function __construct(
        private OrderRepository $orderRepository,
    ) {
    }

    /**
     * @throws OrderNotFound
     */
    public function __invoke(DownloadOrderShippingLabel $message): void
    {
        $order = $this->orderRepository->get($message->orderId);

        $labelUrl = sprintf(
            'https://api.services.omnicado.com/api/label/%s?packageCount=%d&newStatus=shipping',
            $order->number,
            $message->packageCount,
        );

        $order->attachShippingLabel($labelUrl);
    }
}
