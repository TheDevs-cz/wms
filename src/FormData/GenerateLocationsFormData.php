<?php

declare(strict_types=1);

namespace TheDevs\WMS\FormData;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Regex;
use TheDevs\WMS\Entity\Warehouse;

final class GenerateLocationsFormData
{
    #[NotBlank]
    #[Length(min: 2, max: 255)]
    #[Regex(pattern: '/.*{cislo}.*/', message: 'Musí obsahovat "{cislo}".')]
    public string $pattern = '';

    #[NotBlank]
    public null|Warehouse $warehouse = null;

    #[Range(min: 1)]
    public int $start = 0;

    #[Range(min: 1)]
    public int $end = 0;
}
