<?php

declare(strict_types=1);

namespace TheDevs\WMS\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping\UniqueConstraint;
use JetBrains\PhpStorm\Immutable;
use Ramsey\Uuid\Doctrine\UuidType;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Serializer\Attribute\Context;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;
use TheDevs\WMS\Api\ApiResource\CreateOrderRequest;
use TheDevs\WMS\Api\Processor\CreateOrderProcessor;
use TheDevs\WMS\Doctrine\AddressDoctrineType;
use TheDevs\WMS\Events\OrderReceived;
use TheDevs\WMS\Events\OrderStatusChanged;
use TheDevs\WMS\Exceptions\InsufficientStockItemQuantity;
use TheDevs\WMS\Exceptions\OrderItemAlreadyFullyPrepared;
use TheDevs\WMS\Exceptions\OrderItemNotFound;
use TheDevs\WMS\Message\Order\CancelOrder;
use TheDevs\WMS\Value\Address;
use TheDevs\WMS\Value\OrderStatus;

#[Entity]
#[Table(name: '`order`')]
#[UniqueConstraint(name: 'unique_number', columns: ['number', 'user_id'])]
#[ApiResource]
#[Post(input: CreateOrderRequest::class, processor: CreateOrderProcessor::class)]
#[Put(uriTemplate: '/orders/cancel', status: 202, input: CancelOrder::class, output: false, messenger: 'input')]
#[Get]
class Order implements EntityWithEvents
{
    use HasEvents;

    #[Immutable(Immutable::PRIVATE_WRITE_SCOPE)]
    #[Column(nullable: true)]
    public null|string $shippingLabel = null;

    #[Immutable(Immutable::PRIVATE_WRITE_SCOPE)]
    #[Column]
    public OrderStatus $status;

    /** @var Collection<int, OrderItem>  */
    #[Immutable(Immutable::PRIVATE_WRITE_SCOPE)]
    #[OneToMany(targetEntity: OrderItem::class, mappedBy: 'order')]
    public Collection $items;

    public function __construct(
        #[Id]
        #[Immutable]
        #[Column(type: UuidType::NAME, unique: true)]
        public UuidInterface $id,

        #[ApiProperty(readable: false)]
        #[ManyToOne]
        #[Immutable]
        #[JoinColumn(nullable: false)]
        public User $user,

        #[Column(type: Types::DATETIME_IMMUTABLE)]
        readonly public DateTimeImmutable $orderedAt,

        #[Context([DateTimeNormalizer::FORMAT_KEY => 'Y-m-d'])]
        #[Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
        readonly public null|DateTimeImmutable $expeditionDate,

        #[Column]
        readonly public string $number,

        #[Column]
        readonly public float $price,

        #[Column]
        readonly public float $cashOnDelivery,

        #[Column]
        readonly public float $paymentPrice,

        #[Column]
        readonly public float $deliveryPrice,

        #[Column(nullable: true)]
        readonly public null|string $carrier,

        #[Column(nullable: true)]
        readonly public null|string $email,

        #[Column(nullable: true)]
        readonly public null|string $phone,

        #[Immutable(Immutable::PRIVATE_WRITE_SCOPE)]
        #[Column(type: AddressDoctrineType::NAME)]
        public Address $deliveryAddress,
    ) {
        $this->status = OrderStatus::Open;
        $this->items = new ArrayCollection();

        $this->recordThat(
            new OrderReceived(
                $id,
            ),
        );
    }

    public function itemsCount(): int
    {
        return $this->items->count();
    }

    public function addItem(OrderItem $item): void
    {
        if ($this->items->contains($item) === false) {
            $this->items->add($item);
        }
    }

    public function markAsProblematic(UuidInterface $userId, DateTimeImmutable $now): void
    {
        $this->changeStatus(OrderStatus::Problem, $userId, $now);
    }

    public function pack(UuidInterface $userId, DateTimeImmutable $now): void
    {
        $this->changeStatus(OrderStatus::Packed, $userId, $now);
    }

    public function startPacking(UuidInterface $userId, DateTimeImmutable $now): void
    {
        $this->changeStatus(OrderStatus::Packing, $userId, $now);
    }

    public function ship(UuidInterface $userId, DateTimeImmutable $now): void
    {
        $this->changeStatus(OrderStatus::Shipped, $userId, $now);
    }

    public function return(UuidInterface $userId, DateTimeImmutable $now): void
    {
        $this->changeStatus(OrderStatus::Returned, $userId, $now);
    }

    public function cancel(UuidInterface $userId, DateTimeImmutable $now): void
    {
        $this->changeStatus(OrderStatus::Cancelled, $userId, $now);
    }

    /**
     * @throws OrderItemAlreadyFullyPrepared
     * @throws OrderItemNotFound
     * @throws InsufficientStockItemQuantity
     */
    public function pickItem(
        StockItem $stockItem,
        UuidInterface $userId,
        int $quantity,
        DateTimeImmutable $now
    ): void
    {
        foreach ($this->items as $orderItem) {
            if ($orderItem->ean !== $stockItem->ean) {
                continue;
            }

            $stockItem->unload($userId, $quantity, $this->id, $now);
            $orderItem->pick($quantity);

            $this->changeStatus(OrderStatus::Picking, $userId, $now);

            if ($this->isFullyPicked()) {
                $this->changeStatus(OrderStatus::Completed, $userId, $now);
            }

            return;
        }

        throw new OrderItemNotFound();
    }

    public function isFullyPicked(): bool
    {
        foreach ($this->items as $item) {
            if ($item->isFullyPrepared() === false) {
                return false;
            }
        }

        return true;
    }

    public function attachShippingLabel(string $label): void
    {
        $this->shippingLabel = $label;
    }

    public function isFinalStatus(): bool
    {
        return $this->status === OrderStatus::Shipped
            || $this->status === OrderStatus::Cancelled
            || $this->status === OrderStatus::Returned;
    }

    public function canBePicked(): bool
    {
        return $this->status === OrderStatus::Open
            || $this->status === OrderStatus::Picking;
    }

    public function canStartPacking(): bool
    {
        return $this->status === OrderStatus::Completed;
    }

    public function canBePacked(): bool
    {
        return $this->status === OrderStatus::Packing;
    }

    public function canBeShipped(): bool
    {
        return $this->status === OrderStatus::Packed;
    }

    public function canPrintLabel(): bool
    {
        return $this->shippingLabel !== null
            || $this->status === OrderStatus::Picking
            || $this->status === OrderStatus::Completed
            || $this->status === OrderStatus::Packing
            || $this->status === OrderStatus::Packed;
    }

    public function canMarkAsProblematic(): bool
    {
        return $this->status !== OrderStatus::Problem
            && $this->status !== OrderStatus::Shipped
            && $this->status !== OrderStatus::Cancelled
            && $this->status !== OrderStatus::Returned;
    }

    public function canBeReturned(): bool
    {
        return $this->status === OrderStatus::Shipped;
    }

    private function changeStatus(OrderStatus $newStatus, UuidInterface $byUserId, DateTimeImmutable $now): void
    {
        if ($this->status !== $newStatus) {
            $this->recordThat(
                new OrderStatusChanged(
                    $this->id,
                    $byUserId,
                    $this->status,
                    $newStatus,
                    $now,
                ),
            );
        }

        $this->status = $newStatus;
    }
}
