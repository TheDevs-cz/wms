<?php

declare(strict_types=1);

namespace TheDevs\WMS\Tests\Controller\Authentication;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use TheDevs\WMS\Tests\TestingLogin;

final class LoginControllerTest extends WebTestCase
{
    public function testResponseIsOk(): void
    {
        $browser = self::createClient();

        $browser->request('GET', '/login');

        $this->assertResponseIsSuccessful();
    }


    public function testRedirectLoggedUsersToHomepage(): void
    {
        $browser = self::createClient();
        
        TestingLogin::logInAsUser($browser, 'user1@test.cz');

        $browser->request('GET', '/login');

        $this->assertResponseRedirects('/');
    }
}
