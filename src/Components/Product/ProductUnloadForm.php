<?php

declare(strict_types=1);

namespace TheDevs\WMS\Components\Product;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use TheDevs\WMS\Entity\Product;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\FormData\ProductStockFormData;
use TheDevs\WMS\FormType\ProductStockFormType;
use TheDevs\WMS\Message\Stock\UnloadStockItem;

#[AsLiveComponent]
final class ProductUnloadForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public null|Product $product = null;

    public function __construct(
        readonly private MessageBusInterface $bus,
    ) {
    }

    /**
     * @return FormInterface<ProductStockFormData>
     */
    protected function instantiateForm(): FormInterface
    {
        $data = new ProductStockFormData();

        return $this->createForm(ProductStockFormType::class, $data, [
            'product' => $this->product,
            'only_available_positions' => true,
        ]);
    }

    #[LiveAction]
    public function handleSubmit(
        #[CurrentUser] User $user,
    ): Response
    {
        $this->submitForm();

        /** @var ProductStockFormData $data */
        $data = $this->getForm()->getData();
        assert($data->position !== null);

        $product = $this->product;
        assert($product !== null);

        $this->bus->dispatch(
            new UnloadStockItem(
                $user->id,
                $data->position->id,
                $product->ean,
                $data->quantity,
            )
        );

        $this->addFlash('success', 'Produkt vyskladněn z pozice');

        return $this->redirectToRoute('product_info', [
            'id' => $product->id,
        ]);
    }
}
