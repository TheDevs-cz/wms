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
use Doctrine\ORM\Mapping\OneToMany;
use JetBrains\PhpStorm\Immutable;
use Ramsey\Uuid\Doctrine\UuidType;
use Ramsey\Uuid\UuidInterface;

#[Entity]
class Warehouse
{
    #[Immutable(Immutable::PRIVATE_WRITE_SCOPE)]
    #[Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    public null|DateTimeImmutable $deactivatedAt = null;

    /** @var Collection<int, Location>  */
    #[Immutable(Immutable::PRIVATE_WRITE_SCOPE)]
    #[OneToMany(targetEntity: Location::class, mappedBy: 'warehouse')]
    private Collection $locations;

    public function __construct(
        #[Id]
        #[Immutable]
        #[Column(type: UuidType::NAME, unique: true)]
        public UuidInterface $id,

        #[Column(type: Types::DATETIME_IMMUTABLE)]
        readonly public DateTimeImmutable $createdAt,

        #[Immutable(Immutable::PRIVATE_WRITE_SCOPE)]
        #[Column]
        public string $title,
    ) {
        $this->locations = new ArrayCollection();
    }

    public function edit(string $title): void
    {
        $this->title = $title;
    }

    public function deactivate(DateTimeImmutable $deactivatedAt): void
    {
        $this->deactivatedAt = $deactivatedAt;
    }

    public function locationsCount(): int
    {
        return $this->locations->count();
    }
}
