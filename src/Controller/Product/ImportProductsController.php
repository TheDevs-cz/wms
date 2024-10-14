<?php

declare(strict_types=1);

namespace TheDevs\WMS\Controller\Product;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\FormData\ImportProductFormData;
use TheDevs\WMS\FormType\ImportProductFormType;
use TheDevs\WMS\Message\Product\ImportProducts;

final class ImportProductsController extends AbstractController
{
    public function __construct(
        readonly private MessageBusInterface $bus,
    ) {
    }

    #[Route(path: '/admin/products/import', name: 'product_import')]
    #[IsGranted(User::ROLE_ADMIN)]
    public function __invoke(Request $request, #[CurrentUser] User $user): Response
    {
        $data = new ImportProductFormData();
        $form = $this->createForm(ImportProductFormType::class, $data);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            assert($data->file !== null);

            $this->bus->dispatch(
                new ImportProducts(
                    $user->id,
                    $data->file,
                ),
            );

            $this->addFlash('success', 'Import byl zařazen do fronty - bude zpracován na pozadí');

            return $this->redirectToRoute('products');
        }

        return $this->render('product/import.html.twig', [
            'form' => $form,
        ]);
    }
}
