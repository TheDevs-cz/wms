<?php

declare(strict_types=1);

namespace TheDevs\WMS\Tests\Controller\User;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use TheDevs\WMS\Tests\TestingLogin;

final class EditUserProfileControllerTest extends WebTestCase
{
    public function testAnonymousUserWillBeRedirectedToLogin(): void
    {
        $browser = self::createClient();

        $browser->request('GET', '/edit-profile');

        $this->assertResponseRedirects('/login');
    }

    public function testPageCanBeRendered(): void
    {
        $browser = self::createClient();

        TestingLogin::logInAsUser($browser, 'user1@test.cz');

        $browser->request('GET', '/edit-profile');

        $this->assertResponseIsSuccessful();
    }
}
