<?php

declare(strict_types=1);

namespace TheDevs\WMS\FormData;

use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

final class UserFormData
{
    #[NotBlank]
    #[Email]
    public string $email = '';

    public null|string $name = null;

    public null|string $password = null;

    #[NotBlank]
    public null|string $role = null;
}
