<?php

declare(strict_types=1);

namespace TheDevs\WMS\Entity;

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
use JetBrains\PhpStorm\Immutable;
use Ramsey\Uuid\Doctrine\UuidType;
use Ramsey\Uuid\UuidInterface;
use TheDevs\WMS\Doctrine\AddressDoctrineType;
use TheDevs\WMS\Events\OrderReceived;
use TheDevs\WMS\Value\Address;
use TheDevs\WMS\Value\OrderStatus;

#[Entity]
#[Table(name: '`order`')]
class Order implements EntityWithEvents
{
    use HasEvents;

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

        #[ManyToOne]
        #[Immutable]
        #[JoinColumn(nullable: false)]
        public User $user,

        #[Column(type: Types::DATETIME_IMMUTABLE)]
        readonly public DateTimeImmutable $orderedAt,

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

        #[Column]
        readonly public string $carrier,

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
}
