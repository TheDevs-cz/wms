<?php

declare(strict_types=1);

namespace TheDevs\WMS\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use TheDevs\WMS\Repository\UserRepository;

readonly final class TestingLogin
{
    public static function logInAsUser(KernelBrowser $browser, string $email): void
    {
        $container = $browser->getContainer();

        $repository = $container->get(UserRepository::class);
        $user = $repository->get($email);

        $browser->loginUser($user);
    }
}
