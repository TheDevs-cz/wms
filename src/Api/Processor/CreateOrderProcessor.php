<?php

declare(strict_types=1);

namespace TheDevs\WMS\Api\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Messenger\MessageBusInterface;
use TheDevs\WMS\Api\ApiResource\CreateOrderRequest;
use TheDevs\WMS\Entity\Order;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\Message\Order\CreateOrder;
use TheDevs\WMS\Repository\OrderRepository;
use TheDevs\WMS\Services\ProvideIdentity;


/**
 * @implements ProcessorInterface<CreateOrderRequest, Order>
 */
readonly final class CreateOrderProcessor implements ProcessorInterface
{
    public function __construct(
        private Security $security,
        private ProvideIdentity $provideIdentity,
        private MessageBusInterface $bus,
        private OrderRepository $orderRepository,
    ) {
    }

    /**
     * @param CreateOrderRequest $data
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): Order
    {
        $orderId = $this->provideIdentity->next();
        $user = $this->security->getUser();

        assert($user instanceof User);
        assert($data->deliveryAddress !== null);
        assert($data->orderedAt !== null);

        $this->bus->dispatch(
            new CreateOrder(
                id: $orderId,
                userId: $user->id,
                number: $data->number,
                price: $data->price,
                cashOnDelivery: $data->cashOnDelivery,
                paymentPrice: $data->paymentPrice,
                deliveryPrice: $data->deliveryPrice,
                carrier: $data->carrier,
                deliveryAddress: $data->deliveryAddress,
                orderedAt: $data->orderedAt,
                expeditionDate: $data->expeditionDate,
                items: $data->items,
            ),
        );

        return $this->orderRepository->get($orderId);
    }
}
