<?php

declare(strict_types=1);

namespace TheDevs\WMS\Controller\Position;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\Entity\Position;
use TheDevs\WMS\Message\Position\DeactivatePosition;

final class DeactivatePositionController extends AbstractController
{
    public function __construct(
        readonly private MessageBusInterface $bus,
    ) {
    }

    #[Route(path: '/admin/position/{id}/deactivate', name: 'position_deactivate')]
    #[IsGranted(User::ROLE_ADMIN)]
    public function __invoke(Position $position): Response
    {
        $this->bus->dispatch(
            new DeactivatePosition($position->id),
        );

        $this->addFlash('success', 'Sklad deaktivován');

        return $this->redirectToRoute('positions');
    }
}
