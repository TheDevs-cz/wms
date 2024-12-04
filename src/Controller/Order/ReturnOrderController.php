<?php

declare(strict_types=1);

namespace TheDevs\WMS\Controller\Order;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\Order;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\Message\Order\ReturnOrder;

final class ReturnOrderController extends AbstractController
{
    public function __construct(
        readonly private MessageBusInterface $bus,
    ) {
    }

    #[Route(path: '/order/{id}/return', name: 'order_return')]
    #[IsGranted(User::ROLE_WAREHOUSEMAN)]
    public function __invoke(Order $order, #[CurrentUser] User $user): Response
    {
        $this->bus->dispatch(
            new ReturnOrder(
                userId: $user->id,
                orderId: $order->id,
            ),
        );

        $this->addFlash('success', 'Objednávka byla označená jako vratka.');

        return $this->redirectToRoute('order_detail', [
            'id' => $order->id,
        ]);
    }
}
