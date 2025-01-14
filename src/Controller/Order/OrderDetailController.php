<?php

declare(strict_types=1);

namespace TheDevs\WMS\Controller\Order;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\Order;
use TheDevs\WMS\Services\Security\OrderVoter;

final class OrderDetailController extends AbstractController
{
    #[Route(path: '/order/{id}', name: 'order_detail')]
    #[IsGranted(OrderVoter::VIEW, 'order')]
    public function __invoke(Order $order): Response
    {
        return $this->render('order/detail.html.twig', [
            'order' => $order,
        ]);
    }
}
