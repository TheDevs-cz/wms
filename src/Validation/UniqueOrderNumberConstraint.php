<?php

declare(strict_types=1);

namespace TheDevs\WMS\Validation;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
final class UniqueOrderNumberConstraint extends Constraint
{
    public string $message = 'Objednávka s číslem {{ value }} již existuje.';
}
