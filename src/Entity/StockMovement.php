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
class StockMovement
{
    public function __construct(
        #[Id]
        #[Immutable]
        #[Column(type: UuidType::NAME, unique: true)]
        public UuidInterface $id,

        #[Immutable]
        #[ManyToOne]
        #[JoinColumn(nullable: false)]
        public User $author,

        #[Column]
        readonly public string $ean,

        #[Column(nullable: true)]
        readonly public null|string $sku,

        #[Column]
        readonly public int $oldQuantity,

        #[Column]
        readonly public int $newQuantity,

        #[Immutable]
        #[ManyToOne]
        public null|Product $product,

        #[Immutable]
        #[ManyToOne]
        public null|Position $fromPosition,

        #[Immutable]
        #[ManyToOne]
        public null|Position $toPosition,

        #[Immutable]
        #[ManyToOne]
        public null|Order $order,

        #[Column(type: Types::DATETIME_IMMUTABLE)]
        readonly public DateTimeImmutable $movedAt,
    ) {
    }
}
