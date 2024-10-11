<?php

declare(strict_types=1);

namespace TheDevs\WMS\FormData;

use TheDevs\WMS\Entity\User;

final class UserProfileFormData
{
    public null|string $name = null;

    public static function fromUser(User $user): self
    {
        $self = new self();
        $self->name = $user->name;

        return $self;
    }
}
