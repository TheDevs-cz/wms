<?php

declare(strict_types=1);

namespace TheDevs\WMS\Entity;

use ApiPlatform\Metadata\ApiProperty;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use JetBrains\PhpStorm\Immutable;
use Ramsey\Uuid\Doctrine\UuidType;
use Ramsey\Uuid\UuidInterface;
use TheDevs\WMS\Events\OrderItemPrepared;
use TheDevs\WMS\Exceptions\OrderItemAlreadyFullyPrepared;

#[Entity]
class OrderItem implements EntityWithEvents
{
    use HasEvents;

    #[Immutable(Immutable::PRIVATE_WRITE_SCOPE)]
    #[Column(options: ['default' => 0])]
    public int $preparedQuantity = 0;

    public function __construct(
        #[Id]
        #[Immutable]
        #[Column(type: UuidType::NAME, unique: true)]
        public UuidInterface $id,

        #[ApiProperty(readable: false)]
        #[ManyToOne(inversedBy: 'items')]
        #[Immutable]
        #[JoinColumn(nullable: false)]
        public Order $order,

        #[ApiProperty(readable: false)]
        #[ManyToOne]
        #[Immutable]
        #[JoinColumn]
        public null|Product $product,

        #[Column]
        public string $title,

        #[Column]
        readonly public int $quantity,

        #[Column]
        public string $ean,

        #[Column]
        public float $itemPrice,

        #[Column(nullable: true)]
        public null|string $sku = null,

        /** @var null|array<string> */
        #[Column(type: Types::JSON, nullable: true)]
        public null|array $serialNumbers = null,
    ) {
        $order->addItem($this);
    }

    /**
     * @throws OrderItemAlreadyFullyPrepared
     */
    public function prepareForExpedition(UuidInterface $userId, UuidInterface $id, int $quantity): void
    {
        if (($this->quantity - $this->preparedQuantity - $quantity) < 0) {
            throw new OrderItemAlreadyFullyPrepared();
        }

        $this->preparedQuantity += $quantity;

        $this->recordThat(
            new OrderItemPrepared(),
        );
    }

    public function isFullyPrepared(): bool
    {
        return $this->quantity === $this->preparedQuantity;
    }
}
