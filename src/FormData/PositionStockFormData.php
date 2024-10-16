<?php

declare(strict_types=1);

namespace TheDevs\WMS\FormData;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use TheDevs\WMS\Validation\EANConstraint;

final class PositionStockFormData
{
    #[Range(min: 1, max: 1000)]
    public int $quantity = 1;

    #[NotBlank]
    #[EANConstraint]
    public string $code = '';
}
