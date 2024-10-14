<?php

declare(strict_types=1);

namespace TheDevs\WMS\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\User;

final class AddUserController extends AbstractController
{
    #[Route(path: '/admin/users/add', name: 'user_add')]
    #[IsGranted(User::ROLE_ADMIN)]
    public function __invoke(): Response
    {
        return $this->render('user/add.html.twig');
    }
}
