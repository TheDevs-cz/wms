<?php

declare(strict_types=1);

namespace TheDevs\WMS\Controller\Order;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\Order;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\Query\StockItemQuery;

final class PrepareOrderItemController extends AbstractController
{
    public function __construct(
        readonly private StockItemQuery $stockItemQuery,
    ) {
    }

    #[Route(path: '/order/{id}/pick-item', name: 'order_prepare_item')]
    #[IsGranted(User::ROLE_WAREHOUSEMAN)]
    public function __invoke(Order $order): Response
    {
        return $this->render('order/prepare_item.html.twig', [
            'order' => $order,
            'stockItems' => $this->stockItemQuery->getForOrder($order->id),
        ]);
    }
}
