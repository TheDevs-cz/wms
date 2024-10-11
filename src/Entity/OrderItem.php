<?php

declare(strict_types=1);

namespace TheDevs\WMS\Entity;

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

        #[ManyToOne]
        #[Immutable]
        #[JoinColumn(nullable: false, onDelete: 'CASCADE')]
        public Order $order,

        #[ManyToOne]
        #[Immutable]
        #[JoinColumn(nullable: false, onDelete: 'CASCADE')]
        public Product $product,

        #[Immutable]
        #[Column]
        public int $quantity,
    ) {
        // TODO: price?
    }
}
