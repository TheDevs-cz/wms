<?php

declare(strict_types=1);

namespace TheDevs\WMS\Controller\Location;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\Location;
use TheDevs\WMS\Entity\User;

final class LocationQrBatchController extends AbstractController
{
    #[Route(path: '/admin/location/{id}/qr-batch', name: 'location_qr_batch')]
    #[IsGranted(User::ROLE_ADMIN)]
    public function __invoke(
        Location $location,
    ): Response
    {
        return $this->render('location/qr_batch.html.twig', [
            'locations' => $location,
            'positions' => $location->positions(),
        ]);
    }
}
