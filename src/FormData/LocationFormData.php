<?php

declare(strict_types=1);

namespace TheDevs\WMS\FormData;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use TheDevs\WMS\Entity\Warehouse;

final class LocationFormData
{
    #[NotBlank]
    #[Length(min: 2, max: 255)]
    public string $name = '';

    #[NotBlank]
    public null|Warehouse $warehouse = null;
}
