<?php
declare(strict_types=1);

namespace TheDevs\WMS\Controller;

use Symfony\Component\Security\Http\Attribute\CurrentUser;
use TheDevs\WMS\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use TheDevs\WMS\Query\OrderQuery;
use TheDevs\WMS\Query\WarehouseQuery;
use TheDevs\WMS\Repository\WarehouseRepository;

final class DashboardController extends AbstractController
{
    public function __construct(
        readonly private WarehouseQuery $warehouseQuery,
        readonly private OrderQuery $orderQuery,
    ) {
    }

    #[Route(path: '/', name: 'dashboard')]
    public function __invoke(#[CurrentUser] User $user): Response
    {
        if ($this->isGranted(User::ROLE_WAREHOUSEMAN)) {
            $warehouses = $this->warehouseQuery->getAll();
            $positionsCount = $this->warehouseQuery->positionsCount();
            $userId = null;
        } else {
            $warehouses = [];
            $positionsCount = [];
            $userId = $user->id;
        }

        $countOpenOrders = $this->orderQuery->countOpenOrders($userId);

        return $this->render('dashboard.html.twig', [
            'warehouses' => $warehouses,
            'positionsCount' => $positionsCount,
            'openOrdersCount' => $countOpenOrders,
        ]);
    }
}
