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
class Product
{
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
        #[JoinColumn(nullable: false)]
        public User $user,

        #[Column]
        readonly public string $sku,

        #[Column]
        readonly public string $ean,

        #[Column(type: Types::DATETIME_IMMUTABLE)]
        readonly public DateTimeImmutable $importedAt,

        #[Column]
        public string $title,

        #[Column(nullable: true)]
        public null|string $image,
    ) {
    }
}
