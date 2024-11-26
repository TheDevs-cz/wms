<?php

declare(strict_types=1);

namespace TheDevs\WMS\Controller\Warehouse;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\Entity\Warehouse;

final class WarehouseStockDemandsController extends AbstractController
{

    #[Route(path: '/warehouse/{id}/stock-demands', name: 'warehouse_stock_demands')]
    #[IsGranted(User::ROLE_WAREHOUSEMAN)]
    public function __invoke(Warehouse $warehouse): Response
    {
        return $this->render('warehouse/stock_demands.html.twig', [
            'warehouse' => $warehouse,
        ]);
    }
}
