<?php

declare(strict_types=1);

namespace TheDevs\WMS\Api\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

final class OrderItemResource
{
    #[ApiProperty(example: 'My awesome product')]
    #[NotBlank]
    #[Length(max: 200)]
    public string $title = '';

    #[Range(min: 1, max: 1000)]
    public int $quantity = 0;

    #[ApiProperty(example: '0123456789123')]
    #[NotBlank]
    #[Length(max: 20)]
    public string $ean = '';

    #[ApiProperty(example: 100.2)]
    #[Range(min: 1, max: 999999)]
    public float $itemPrice = 0;

    #[ApiProperty(example: 'ABC-001')]
    #[Length(0, 100)]
    public null|string $sku = null;

    /** @var null|array<string> */
    #[ApiProperty(example: ['sn1', 'sn2'])]
    public null|array $serialNumbers = null;
}
