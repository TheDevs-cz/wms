<?php

declare(strict_types=1);

namespace TheDevs\WMS\Controller\Warehouse;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\Entity\Warehouse;
use TheDevs\WMS\Message\Warehouse\DeactivateWarehouse;

final class DeactivateWarehouseController extends AbstractController
{
    public function __construct(
        readonly private MessageBusInterface $bus,
    ) {
    }

    #[Route(path: '/admin/warehouse/{id}/deactivate', name: 'deactivate_warehouse')]
    #[IsGranted(User::ROLE_ADMIN)]
    public function __invoke(Warehouse $warehouse): Response
    {
        $this->bus->dispatch(
            new DeactivateWarehouse($warehouse->id),
        );

        $this->addFlash('success', 'Sklad deaktivován');

        return $this->redirectToRoute('warehouses');
    }
}
