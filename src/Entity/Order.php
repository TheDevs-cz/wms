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
use Doctrine\ORM\Mapping\Table;
use JetBrains\PhpStorm\Immutable;
use Ramsey\Uuid\Doctrine\UuidType;
use Ramsey\Uuid\UuidInterface;
use TheDevs\WMS\Value\OrderStatus;

#[Entity]
#[Table(name: '`order`')]
class Order
{
    public function __construct(
        #[Id]
        #[Immutable]
        #[Column(type: UuidType::NAME, unique: true)]
        public UuidInterface $id,

        #[ManyToOne]
        #[Immutable]
        #[JoinColumn(nullable: false, onDelete: "CASCADE")]
        public User $user,

        #[Column(type: Types::DATETIME_IMMUTABLE)]
        readonly public DateTimeImmutable $orderedAt,

        #[Column]
        readonly public string $number,

        #[Immutable(Immutable::PRIVATE_WRITE_SCOPE)]
        #[Column]
        public OrderStatus $status,
    ) {
        // TODO: address
        // TODO: items
        // TODO: carrier (+ price)?
        // TODO: cod (+ price)?
    }
}
