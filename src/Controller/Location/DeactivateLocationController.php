<?php

declare(strict_types=1);

namespace TheDevs\WMS\Controller\Location;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\Entity\Location;
use TheDevs\WMS\Message\Location\DeactivateLocation;

final class DeactivateLocationController extends AbstractController
{
    public function __construct(
        readonly private MessageBusInterface $bus,
    ) {
    }

    #[Route(path: '/admin/location/{id}/deactivate', name: 'location_deactivate')]
    #[IsGranted(User::ROLE_ADMIN)]
    public function __invoke(Location $location): Response
    {
        $this->bus->dispatch(
            new DeactivateLocation($location->id),
        );

        $this->addFlash('success', 'Sklad deaktivován');

        return $this->redirectToRoute('locations');
    }
}
