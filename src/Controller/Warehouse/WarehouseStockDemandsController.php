<?php

declare(strict_types=1);

namespace TheDevs\WMS\Controller\Warehouse;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\Query\StockItemQuery;

final class WarehouseStockDemandsController extends AbstractController
{
    public function __construct(
        readonly private StockItemQuery $stockItemQuery,
    ) {
    }

    #[Route(path: '/stock-demands', name: 'warehouse_stock_demands')]
    #[IsGranted(User::ROLE_CUSTOMER)]
    public function __invoke(#[CurrentUser] User $user): Response
    {
        if ($this->isGranted(User::ROLE_WAREHOUSEMAN)) {
            $stockDemands = $this->stockItemQuery->getStockDemand();
        } else {
            $stockDemands = $this->stockItemQuery->getStockDemandForUser($user->id);
        }

        return $this->render('warehouse/stock_demands.html.twig', [
            'stock_demands' => $stockDemands,
        ]);
    }
}
