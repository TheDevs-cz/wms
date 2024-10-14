<?php

declare(strict_types=1);

namespace TheDevs\WMS\Controller\User;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\Query\UserQuery;

final class UsersController extends AbstractController
{
    public function __construct(
        readonly private UserQuery $userQuery,
    ) {
    }

    #[Route(path: '/admin/users', name: 'users')]
    #[IsGranted(User::ROLE_ADMIN)]
    public function __invoke(): Response
    {
        return $this->render('user/list.html.twig', [
            'users' => $this->userQuery->getAll(),
        ]);
    }
}
