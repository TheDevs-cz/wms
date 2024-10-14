<?php

declare(strict_types=1);

namespace TheDevs\WMS\Controller\Location;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\Entity\Location;

final class LocationInfoController extends AbstractController
{
    #[Route(path: '/admin/location/{id}', name: 'location_info')]
    #[IsGranted(User::ROLE_WAREHOUSEMAN)]
    public function __invoke(Location $location): Response
    {
        return $this->render('location/info.html.twig', [
            'location' => $location,
        ]);
    }
}
