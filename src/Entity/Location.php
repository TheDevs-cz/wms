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
use JetBrains\PhpStorm\Immutable;
use Ramsey\Uuid\Doctrine\UuidType;
use Ramsey\Uuid\UuidInterface;

#[Entity]
class Location
{
    #[Immutable(Immutable::PRIVATE_WRITE_SCOPE)]
    #[Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    public null|DateTimeImmutable $deactivatedAt = null;

    /** @var Collection<int, Position>  */
    #[Immutable(Immutable::PRIVATE_WRITE_SCOPE)]
    #[OneToMany(targetEntity: Position::class, mappedBy: 'location')]
    private Collection $positions;

    public function __construct(
        #[Id]
        #[Immutable]
        #[Column(type: UuidType::NAME, unique: true)]
        public UuidInterface $id,

        #[ManyToOne(fetch: 'EAGER', inversedBy: 'locations')]
        #[Immutable(Immutable::PRIVATE_WRITE_SCOPE)]
        #[JoinColumn(nullable: false)]
        public Warehouse $warehouse,

        #[Column(type: Types::DATETIME_IMMUTABLE)]
        readonly public DateTimeImmutable $createdAt,

        #[Column]
        public string $name,
    ) {
        $this->positions = new ArrayCollection();
    }

    public function edit(Warehouse $warehouse, string $name): void
    {
        $this->warehouse = $warehouse;
        $this->name = $name;
    }

    public function deactivate(DateTimeImmutable $deactivatedAt): void
    {
        $this->deactivatedAt = $deactivatedAt;
    }

    public function positionsCount(): int
    {
        return $this->positions->count();
    }

    /** @return array<Position> */
    public function positions(): array
    {
        return $this->positions->toArray();
    }
}
