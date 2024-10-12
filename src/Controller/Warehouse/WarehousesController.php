<?php

declare(strict_types=1);

namespace TheDevs\WMS\Controller\Warehouse;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\Query\WarehouseQuery;

final class WarehousesController extends AbstractController
{
    public function __construct(
        readonly private WarehouseQuery $warehouseQuery,
    ) {
    }

    #[Route(path: '/admin/warehouses', name: 'warehouses')]
    #[IsGranted(User::ROLE_ADMIN)]
    public function __invoke(): Response
    {
        return $this->render('warehouses_list.html.twig', [
            'warehouses' => $this->warehouseQuery->getAll(),
        ]);
    }
}
