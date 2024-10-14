<?php

declare(strict_types=1);

namespace TheDevs\WMS\Components\Product;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\FormData\ImportProductFormData;
use TheDevs\WMS\FormType\ImportProductFormType;

#[AsLiveComponent]
#[IsGranted(User::ROLE_ADMIN)]
final class ImportProductForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    /**
     * @return FormInterface<ImportProductFormData>
     */
    protected function instantiateForm(): FormInterface
    {
        $data = new ImportProductFormData();

        return $this->createForm(ImportProductFormType::class, $data);
    }
}
