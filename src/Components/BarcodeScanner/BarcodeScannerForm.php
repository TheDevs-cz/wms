<?php

declare(strict_types=1);

namespace TheDevs\WMS\Components\BarcodeScanner;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use TheDevs\WMS\FormData\BarcodeScanFormData;
use TheDevs\WMS\FormType\BarcodeScanFormType;

#[AsLiveComponent]
final class BarcodeScannerForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    /**
     * @return FormInterface<BarcodeScanFormData>
     */
    protected function instantiateForm(): FormInterface
    {
        $data = new BarcodeScanFormData();

        return $this->createForm(BarcodeScanFormType::class, $data);
    }

    #[LiveAction]
    public function handleScan(): Response
    {
        $this->submitForm();

        /** @var BarcodeScanFormData $data */
        $data = $this->getForm()->getData();

        return $this->redirectToRoute('scan_info');
    }
}
