<?php
declare(strict_types=1);

namespace TheDevs\WMS\Controller\Api;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use TheDevs\WMS\Api\ApiResource\CreateOrderRequest;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\Message\Order\CreateOrder;
use TheDevs\WMS\Services\ProvideIdentity;

#[AsController]
final class CreateOrderAction extends AbstractController
{
    public function __construct(
        readonly private MessageBusInterface $bus,
        readonly private ProvideIdentity $provideIdentity,
    ) {
    }

    public function __invoke(CreateOrderRequest $apiOrder, #[CurrentUser] User $user): CreateOrderRequest
    {
        $orderId = $this->provideIdentity->next();

        assert($apiOrder->deliveryAddress !== null);
        assert($apiOrder->orderedAt !== null);

        $this->bus->dispatch(
            new CreateOrder(
                id: $orderId,
                userId: $user->id,
                number: $apiOrder->number,
                price: $apiOrder->price,
                cashOnDelivery: $apiOrder->cashOnDelivery,
                paymentPrice: $apiOrder->paymentPrice,
                deliveryPrice: $apiOrder->deliveryPrice,
                carrier: $apiOrder->carrier,
                deliveryAddress: $apiOrder->deliveryAddress,
                orderedAt: $apiOrder->orderedAt,
                items: $apiOrder->items,
            ),
        );

        $apiOrder->id = $orderId;

        return $apiOrder;
    }
}
