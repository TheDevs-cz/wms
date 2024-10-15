<?php

declare(strict_types=1);

namespace TheDevs\WMS\Controller\Product;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\Product;
use TheDevs\WMS\Entity\User;

final class StockUpProductController extends AbstractController
{
    #[Route(path: '/product/{id}/stock-up', name: 'product_stock_up')]
    #[IsGranted(User::ROLE_WAREHOUSEMAN)]
    public function __invoke(Product $product): Response
    {
        return $this->render('product/stock_up.html.twig', [
            'product' => $product,
        ]);
    }
}
