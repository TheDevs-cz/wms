<?php

declare(strict_types=1);

namespace TheDevs\WMS\Validation;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use TheDevs\WMS\Exceptions\ProductNotFound;
use TheDevs\WMS\Query\ProductQuery;

final class EANConstraintValidator extends ConstraintValidator
{
    public function __construct(
        readonly private ProductQuery $productQuery,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof EANConstraint) {
            throw new UnexpectedTypeException($constraint, EANConstraint::class);
        }

        /* @var $constraint EANConstraint */
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        try {
            $this->productQuery->getByEan($value);
        } catch (ProductNotFound) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->addViolation();
        }
    }
}
