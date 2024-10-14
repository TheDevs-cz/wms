<?php

declare(strict_types=1);

namespace TheDevs\WMS\Controller\Product;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\User;

final class ProductsController extends AbstractController
{
    public function __construct(
    ) {
    }

    #[Route(path: '/admin/products', name: 'products')]
    #[IsGranted(User::ROLE_ADMIN)]
    public function __invoke(): Response
    {
        return $this->render('product/list.html.twig', [
            'products' => [],
        ]);
    }
}
