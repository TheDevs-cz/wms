<?php

declare(strict_types=1);

namespace TheDevs\WMS\FormData;

use Symfony\Component\Validator\Constraints as Assert;

final class BarcodeScanFormData
{
    #[Assert\NotBlank]
    #[Assert\Length(min: 3)]
    public string $code = '';
}
