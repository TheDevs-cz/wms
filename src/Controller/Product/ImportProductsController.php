<?php

declare(strict_types=1);

namespace TheDevs\WMS\Controller\Product;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\User;

final class ImportProductsController extends AbstractController
{
    #[Route(path: '/admin/products/import', name: 'product_import')]
    #[IsGranted(User::ROLE_ADMIN)]
    public function __invoke(): Response
    {
        return $this->render('product/import.html.twig');
    }
}
