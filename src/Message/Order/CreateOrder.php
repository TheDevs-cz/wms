<?php

declare(strict_types=1);

namespace TheDevs\WMS\Message\Order;

use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;
use TheDevs\WMS\Api\ApiResource\OrderItemResource;
use TheDevs\WMS\Value\Address;

readonly final class CreateOrder
{
    public function __construct(
        public UuidInterface $id,
        public UuidInterface $userId,
        public string $number,
        public float $price,
        public float $cashOnDelivery,
        public float $paymentPrice,
        public float $deliveryPrice,
        public string $carrier,
        public Address $deliveryAddress,
        public DateTimeImmutable $orderedAt,
        public null|DateTimeImmutable $expeditionDate,
        /** @var array<OrderItemResource> */
        public array $items = [],
    ) {
    }
}
