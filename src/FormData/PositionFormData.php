<?php

declare(strict_types=1);

namespace TheDevs\WMS\FormData;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use TheDevs\WMS\Entity\Location;
use TheDevs\WMS\Entity\Warehouse;

final class PositionFormData
{
    #[NotBlank]
    #[Length(min: 2, max: 255)]
    public string $name = '';

    #[NotBlank]
    public null|Location $location = null;
}
