<?php

declare(strict_types=1);

namespace TheDevs\WMS\Controller\Position;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\Entity\Position;

final class EditPositionController extends AbstractController
{

    #[Route(path: '/admin/position/{id}/edit', name: 'position_edit')]
    #[IsGranted(User::ROLE_ADMIN)]
    public function __invoke(
        Position $position,
    ): Response
    {
        return $this->render('position/edit.html.twig', [
            'position' => $position,
        ]);
    }
}
