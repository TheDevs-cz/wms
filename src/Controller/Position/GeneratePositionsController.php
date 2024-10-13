<?php

declare(strict_types=1);

namespace TheDevs\WMS\Controller\Position;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\Entity\Position;

final class GeneratePositionsController extends AbstractController
{

    #[Route(path: '/admin/positions/generate', name: 'position_generate')]
    #[IsGranted(User::ROLE_ADMIN)]
    public function __invoke(
        Position $position,
    ): Response
    {
        return $this->render('position/generate.html.twig', [
            'position' => $position,
        ]);
    }
}
