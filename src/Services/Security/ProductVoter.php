<?php

declare(strict_types=1);

namespace TheDevs\WMS\Services\Security;

use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use TheDevs\WMS\Entity\Order;
use TheDevs\WMS\Entity\Product;
use TheDevs\WMS\Entity\User;

/**
 * @extends Voter<string, Product>
 */
final class ProductVoter extends Voter
{
    public const string VIEW = 'product_view';

    public function __construct(
        readonly private Security $security,
    ) {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [self::VIEW])) {
            return false;
        }

        return $subject instanceof Product;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        if ($this->security->isGranted(User::ROLE_WAREHOUSEMAN)) {
            return true;
        }

        if ($subject->user === $user) {
            return true;
        }

        return false;
    }
}
