<?php

declare(strict_types=1);

namespace TheDevs\WMS\Controller\Position;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\Query\PositionQuery;

final class PositionsController extends AbstractController
{
    public function __construct(
        readonly private PositionQuery $positionQuery,
    ) {
    }

    #[Route(path: '/admin/positions', name: 'positions')]
    #[IsGranted(User::ROLE_ADMIN)]
    public function __invoke(): Response
    {
        return $this->render('position/list.html.twig', [
            'positions' => $this->positionQuery->getAll(),
        ]);
    }
}
