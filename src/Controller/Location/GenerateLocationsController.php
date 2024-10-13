<?php

declare(strict_types=1);

namespace TheDevs\WMS\Controller\Location;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\User;

final class GenerateLocationsController extends AbstractController
{

    #[Route(path: '/admin/locations/generate', name: 'location_generate')]
    #[IsGranted(User::ROLE_ADMIN)]
    public function __invoke(): Response
    {
        return $this->render('location/generate.html.twig');
    }
}
