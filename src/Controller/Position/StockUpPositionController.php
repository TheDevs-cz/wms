<?php

declare(strict_types=1);

namespace TheDevs\WMS\Controller\Position;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\Entity\Position;

final class StockUpPositionController extends AbstractController
{
    #[Route(path: '/position/{id}/stock-up', name: 'position_stock_up')]
    #[IsGranted(User::ROLE_WAREHOUSEMAN)]
    public function __invoke(Position $position): Response
    {
        return $this->render('position/stock_up.html.twig', [
            'position' => $position,
        ]);
    }
}
