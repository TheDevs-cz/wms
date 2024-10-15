<?php

declare(strict_types=1);

namespace TheDevs\WMS\Controller\Product;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\Product;
use TheDevs\WMS\Entity\User;

final class UnloadProductController extends AbstractController
{
    #[Route(path: '/product/{id}/unload', name: 'product_unload')]
    #[IsGranted(User::ROLE_WAREHOUSEMAN)]
    public function __invoke(Product $product): Response
    {
        return $this->render('product/unload.html.twig', [
            'product' => $product,
        ]);
    }
}
