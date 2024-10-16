<?php

declare(strict_types=1);

namespace TheDevs\WMS\Validation;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute]
final class EANConstraint extends Constraint
{
    public string $message = 'Produkt s EAN {{ value }} nebyl nalezen.';
}
