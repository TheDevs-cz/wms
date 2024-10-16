<?php

declare(strict_types=1);

namespace TheDevs\WMS\Services\Security;

use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use TheDevs\WMS\Exceptions\UserNotFound;
use TheDevs\WMS\Query\UserQuery;

final class ApiTokenAuthenticator implements AccessTokenHandlerInterface
{
    public function __construct(
        readonly private UserQuery $userQuery,
    ) {
    }

    public function getUserBadgeFrom(#[\SensitiveParameter] string $accessToken): UserBadge
    {
        try {
            $user = $this->userQuery->getByApiToken($accessToken);
        } catch (UserNotFound) {
            throw new BadCredentialsException('Invalid API token');
        }

        return new UserBadge($user->email);
    }
}
