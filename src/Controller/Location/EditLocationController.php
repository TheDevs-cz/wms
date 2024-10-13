<?php

declare(strict_types=1);

namespace TheDevs\WMS\Controller\Location;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\Entity\Location;

final class EditLocationController extends AbstractController
{

    #[Route(path: '/admin/location/{id}/edit', name: 'location_edit')]
    #[IsGranted(User::ROLE_ADMIN)]
    public function __invoke(
        Location $location,
    ): Response
    {
        return $this->render('location/edit.html.twig', [
            'location' => $location,
        ]);
    }
}
