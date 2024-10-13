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

        #[Column]
        public int $quantity,

        #[Column(nullable: true)]
        readonly public null|string $sku,

        #[Column(nullable: true)]
        readonly public null|string $ean,
    ) {
    }
}
