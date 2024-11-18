<?php

declare(strict_types=1);

namespace TheDevs\WMS\Api\ApiResource;

use ApiPlatform\Metadata\ApiProperty;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\Range;
use TheDevs\WMS\Validation\UniqueOrderNumberConstraint;
use TheDevs\WMS\Value\Address;

final class CreateOrderRequest
{
    #[ApiProperty(example: 'A000003')]
    #[UniqueOrderNumberConstraint]
    #[Length(max: 100)]
    #[NotBlank]
    public string $number = '';

    #[ApiProperty(example: 124.5)]
    #[Range(min: 0, max: 999999)]
    public float $price = 0;

    #[ApiProperty(example: 124.5)]
    #[Range(min: 0, max: 999999)]
    public float $cashOnDelivery = 0;

    #[ApiProperty(example: 12.3)]
    #[Range(min: 0, max: 999999)]
    public float $paymentPrice = 0;

    #[ApiProperty(example: 40.2)]
    #[Range(min: 0, max: 999999)]
    public float $deliveryPrice = 0;

    #[ApiProperty(example: 'DPD')]
    #[Length(max: 100)]
    #[NotBlank]
    public string $carrier = '';

    #[ApiProperty(example: 'john@doe.com')]
    #[Email]
    #[Length(max: 100)]
    public null|string $email = null;

    #[ApiProperty(example: '+420731123456789')]
    #[Length(max: 20)]
    public null|string $phone = null;

    #[NotNull]
    public null|Address $deliveryAddress = null;

    #[NotNull]
    public null|DateTimeImmutable $orderedAt = null;

    #[ApiProperty(example: '2024-10-20')]
    public null|DateTimeImmutable $expeditionDate = null;

    /** @var array<OrderItemResource>  */
    #[NotBlank]
    public array $items = [];
}
