<?php

declare(strict_types=1);

namespace TheDevs\WMS\Controller\Order;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\Entity\Warehouse;
use TheDevs\WMS\Query\OrderQuery;

final class OrdersController extends AbstractController
{
    public function __construct(
        readonly private OrderQuery $orderQuery,
    ) {
    }

    #[Route(path: '/orders', name: 'orders')]
    #[IsGranted(User::ROLE_WAREHOUSEMAN)]
    public function __invoke(): Response
    {
        return $this->render('order/list.html.twig', [
            'orders' => $this->orderQuery->getAll(),
        ]);
    }
}
