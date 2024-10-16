<?php

declare(strict_types=1);

namespace TheDevs\WMS\Entity;

use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Id;
use JetBrains\PhpStorm\Immutable;
use Ramsey\Uuid\Doctrine\UuidType;
use Ramsey\Uuid\UuidInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    public const string ROLE_ADMIN = 'ROLE_ADMIN';
    public const string ROLE_WAREHOUSEMAN = 'ROLE_WAREHOUSEMAN';
    public const string ROLE_CUSTOMER = 'ROLE_CUSTOMER';

    #[Immutable]
    #[Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    public null|DateTimeImmutable $deactivatedAt = null;

    #[Immutable(Immutable::PRIVATE_WRITE_SCOPE)]
    #[Column]
    public string $password = '';

    #[Immutable(Immutable::PRIVATE_WRITE_SCOPE)]
    #[Column(nullable: true)]
    public null|string $name = null;

    #[Immutable(Immutable::PRIVATE_WRITE_SCOPE)]
    #[Column(unique: true, nullable: true)]
    public null|string $apiToken = null;

    public function __construct(
        #[Id]
        #[Immutable]
        #[Column(type: UuidType::NAME, unique: true)]
        public UuidInterface $id,

        #[Column(length: 180, unique: true)]
        readonly public string $email,

        #[Column(type: Types::DATETIME_IMMUTABLE)]
        readonly public DateTimeImmutable $registeredAt,

        #[Immutable(Immutable::PRIVATE_WRITE_SCOPE)]
        #[Column(options: ['default' => true])]
        public bool $confirmed = true,

        /** @var array<string> */
        #[Column(type: Types::JSON)]
        private array $roles = [],
    ) {
    }

    public function changePassword(string $hashedPassword): void
    {
        $this->password = $hashedPassword;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    /**
     * @return array<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function getPassword(): string
    {
        // Just to satisfy the interface ...
        return $this->password;
    }

    public function eraseCredentials(): void
    {
        // Just to satisfy the interface ...
    }

    public function displayName(): string
    {
        return $this->name ?? $this->email;
    }

    public function editProfile(
        null|string $name,
    ): void {
        $this->name = $name;
    }
}
