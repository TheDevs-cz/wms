<?php

declare(strict_types=1);

namespace TheDevs\WMS\Components\BarcodeScanner;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use TheDevs\WMS\Exceptions\ProductNotFound;
use TheDevs\WMS\FormData\BarcodeScanFormData;
use TheDevs\WMS\FormType\BarcodeScanFormType;
use TheDevs\WMS\Query\ProductQuery;

#[AsLiveComponent]
final class ScanInfoForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public bool $formSubmitted = false;

    public function __construct(
        readonly private ProductQuery $productQuery,
    ) {
    }

    /**
     * @return FormInterface<BarcodeScanFormData>
     */
    protected function instantiateForm(): FormInterface
    {
        $data = new BarcodeScanFormData();

        return $this->createForm(BarcodeScanFormType::class, $data);
    }

    #[LiveAction]
    public function handleScan()
    {
        $this->submitForm();

        /** @var BarcodeScanFormData $data */
        $data = $this->getForm()->getData();

        if (str_starts_with($data->code, 'http')) {
            return $this->redirect($data->code);
        }

        try {
            $product = $this->productQuery->getByEan($data->code);

            return $this->redirectToRoute('product_info', ['id' => $product->id]);
        } catch (ProductNotFound) {
            $this->formSubmitted = true;
        }
    }
}
