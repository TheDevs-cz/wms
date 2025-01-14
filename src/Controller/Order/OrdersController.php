<?php

declare(strict_types=1);

namespace TheDevs\WMS\Controller\Order;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\Query\OrderQuery;
use TheDevs\WMS\Value\OrdersFilter;

final class OrdersController extends AbstractController
{
    public function __construct(
        readonly private OrderQuery $orderQuery,
    ) {
    }

    #[Route(path: '/orders', name: 'orders')]
    #[IsGranted(User::ROLE_CUSTOMER)]
    public function __invoke(Request $request, #[CurrentUser] User $user): Response
    {
        /** @var string $filterValue */
        $filterValue = $request->query->get('filter', OrdersFilter::Unfinished->value);
        $filter = OrdersFilter::tryFrom($filterValue) ?? OrdersFilter::Unfinished;

        if ($this->isGranted(User::ROLE_WAREHOUSEMAN)) {
            $userId = null;
        } else {
            $userId = $user->id;
        }

        $orders = match ($filter) {
            OrdersFilter::All => $this->orderQuery->getAll($userId),
            OrdersFilter::Finished => $this->orderQuery->getFinished($userId),
            OrdersFilter::Unfinished => $this->orderQuery->getUnfinished($userId),
        };


        return $this->render('order/list.html.twig', [
            'orders' => $orders,
            'active_filter' => $filter,
            'filters' => OrdersFilter::cases(),
        ]);
    }
}
