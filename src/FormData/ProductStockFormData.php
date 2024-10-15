<?php

declare(strict_types=1);

namespace TheDevs\WMS\FormData;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use TheDevs\WMS\Entity\Position;

final class ProductStockFormData
{
    #[Range(min: 1, max: 1000)]
    public int $quantity = 0;

    #[NotBlank]
    public null|Position $position = null;
}
