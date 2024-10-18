<?php

declare(strict_types=1);

namespace TheDevs\WMS\Controller\Warehouse;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\Entity\Warehouse;
use TheDevs\WMS\Query\PositionQuery;

final class WarehouseQrBatchController extends AbstractController
{
    public function __construct(
        readonly private PositionQuery $positionQuery,
    ) {
    }
    
    #[Route(path: '/admin/warehouse/{id}/qr-batch', name: 'warehouse_qr_batch')]
    #[IsGranted(User::ROLE_ADMIN)]
    public function __invoke(
        Warehouse $warehouse,
    ): Response
    {
        return $this->render('warehouse/qr_batch.html.twig', [
            'warehouse' => $warehouse,
            'locations' => $warehouse->locations(),
            'positions' => $this->positionQuery->getByWarehouse($warehouse->id),
        ]);
    }
}
