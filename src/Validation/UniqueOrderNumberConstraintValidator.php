<?php

declare(strict_types=1);

namespace TheDevs\WMS\Validation;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\Exceptions\OrderNotFound;
use TheDevs\WMS\Query\OrderQuery;

final class UniqueOrderNumberConstraintValidator extends ConstraintValidator
{
    public function __construct(
        readonly private OrderQuery $orderQuery,
        readonly private Security $security,
    ) {
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof UniqueOrderNumberConstraint) {
            throw new UnexpectedTypeException($constraint, UniqueOrderNumberConstraint::class);
        }

        /* @var $constraint UniqueOrderNumberConstraint */
        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $user = $this->security->getUser();
        assert($user instanceof User);

        try {
            $this->orderQuery->getByNumberForUser($user->id, $value);

            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ value }}', $this->formatValue($value))
                ->addViolation();
        } catch (OrderNotFound) {
            // Good, order should not bet found!
        }
    }
}
