<?php

declare(strict_types=1);

namespace TheDevs\WMS\Controller\Warehouse;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\Entity\Warehouse;

final class EditWarehouseController extends AbstractController
{

    #[Route(path: '/admin/warehouse/{id}/edit', name: 'edit_warehouse')]
    #[IsGranted(User::ROLE_ADMIN)]
    public function __invoke(
        Warehouse $warehouse,
    ): Response
    {
        return $this->render('edit_warehouse.html.twig', [
            'warehouse' => $warehouse,
        ]);
    }
}
