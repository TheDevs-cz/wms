<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Order;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use TheDevs\WMS\Events\OrderStatusChanged;
use TheDevs\WMS\Repository\OrderRepository;

#[AsMessageHandler]
readonly final class WhenOrderStatusChangedThenNotifyOmnicadoHandler
{
    public function __construct(
        private HttpClientInterface $omnicadoClient,
        private OrderRepository $orderRepository,
    ) {
    }

    public function __invoke(OrderStatusChanged $event): void
    {
        $order = $this->orderRepository->get($event->orderId);
        $omnicadoToken = $order->user->omnicadoToken;

        if ($omnicadoToken === null) {
            return;
        }

        $omnicadoId = $order->number;
        $url = sprintf('/api/v2/orders/status/%s', $omnicadoId);

        $this->omnicadoClient->request('PATCH', $url, [
            'auth_bearer' => $omnicadoToken,
            'json' => [
                'id' => (int) $omnicadoId,
                'externalId' => $order->id->toString(),
                'expeditionStatus' => $order->status->value,
            ],
        ]);
    }
}
