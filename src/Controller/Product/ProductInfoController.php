<?php

declare(strict_types=1);

namespace TheDevs\WMS\Controller\Product;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\Product;
use TheDevs\WMS\Query\StockItemQuery;
use TheDevs\WMS\Query\StockMovementQuery;
use TheDevs\WMS\Services\Security\ProductVoter;

final class ProductInfoController extends AbstractController
{
    public function __construct(
        readonly private StockItemQuery $stockItemQuery,
        readonly private StockMovementQuery $stockMovementQuery,
    ) {
    }

    #[Route(path: '/product/{id}', name: 'product_info')]
    #[IsGranted(ProductVoter::VIEW, 'product')]
    public function __invoke(Product $product): Response
    {
        return $this->render('product/info.html.twig', [
            'product' => $product,
            'movements' => $this->stockMovementQuery->getForProduct($product->id),
            'stock_items' => $this->stockItemQuery->getForProduct($product->id),
        ]);
    }
}
