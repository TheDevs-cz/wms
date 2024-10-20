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

#[Entity]
class OrderItem
{
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
        public float $price,

        #[Column(nullable: true)]
        public null|string $sku = null,

        /** @var null|array<string> */
        #[Column(type: Types::JSON, nullable: true)]
        public null|array $serialNumbers = null,
    ) {
        $order->addItem($this);
    }
}
