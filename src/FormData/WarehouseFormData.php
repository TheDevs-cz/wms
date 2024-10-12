<?php

declare(strict_types=1);

namespace TheDevs\WMS\FormData;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

final class WarehouseFormData
{
    #[NotBlank]
    #[Length(min: 2, max: 255)]
    public string $title = '';
}
