<?php

declare(strict_types=1);

namespace TheDevs\WMS\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use JetBrains\PhpStorm\Immutable;
use Ramsey\Uuid\Doctrine\UuidType;
use Ramsey\Uuid\UuidInterface;
use TheDevs\WMS\Events\ItemAddedToPosition;
use TheDevs\WMS\Events\ItemStockChanged;

#[Entity]
class StockItem implements EntityWithEvents
{
    use HasEvents;

    #[Immutable]
    #[Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    public null|DateTimeImmutable $deactivatedAt = null;

    public function __construct(
        #[Id]
        #[Immutable]
        #[Column(type: UuidType::NAME, unique: true)]
        public UuidInterface $id,

        #[ManyToOne]
        #[Immutable]
        public null|Product $product,

        #[ManyToOne]
        #[Immutable]
        #[JoinColumn(nullable: false)]
        public Position $position,

        #[Column(type: Types::DATETIME_IMMUTABLE)]
        readonly public DateTimeImmutable $stockedAt,

        #[Column(nullable: true)]
        readonly public null|string $sku,

        #[Column]
        readonly public string $ean,

        #[Immutable(Immutable::PRIVATE_WRITE_SCOPE)]
        #[Column]
        public int $quantity,

        UuidInterface $stockedUpByUserId,
    ) {
        $this->recordThat(
            new ItemAddedToPosition($ean, $position->id, $quantity, $stockedUpByUserId, $stockedAt),
        );
    }

    public function stockUp(UuidInterface $userId, int $quantity, DateTimeImmutable $now): void
    {
        $oldQuantity = $this->quantity;
        $this->quantity += $quantity;

        $this->recordThat(
            new ItemStockChanged($this->id, $oldQuantity, $this->quantity, $userId, null, $now),
        );
    }

    public function unload(
        UuidInterface $userId,
        int $quantity,
        null|UuidInterface $orderId,
        DateTimeImmutable $now,
    ): void
    {
        $oldQuantity = $this->quantity;
        $this->quantity -= $quantity;

        $this->recordThat(
            new ItemStockChanged($this->id, $oldQuantity, $this->quantity, $userId, $orderId, $now),
        );
    }
}
