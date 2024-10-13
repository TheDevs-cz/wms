<?php

declare(strict_types=1);

namespace TheDevs\WMS\Controller\Location;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\Query\LocationQuery;

final class LocationsController extends AbstractController
{
    public function __construct(
        readonly private LocationQuery $locationQuery,
    ) {
    }

    #[Route(path: '/admin/locations', name: 'locations')]
    #[IsGranted(User::ROLE_ADMIN)]
    public function __invoke(): Response
    {
        return $this->render('location/list.html.twig', [
            'locations' => $this->locationQuery->getAll(),
        ]);
    }
}
