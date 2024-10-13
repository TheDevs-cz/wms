<?php

declare(strict_types=1);

namespace TheDevs\WMS\Components\Warehouse;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\Entity\Warehouse;
use TheDevs\WMS\FormData\WarehouseFormData;
use TheDevs\WMS\FormType\WarehouseFormType;
use TheDevs\WMS\Message\Warehouse\AddWarehouse;
use TheDevs\WMS\Message\Warehouse\EditWarehouse;

#[AsLiveComponent]
#[IsGranted(User::ROLE_ADMIN)]
final class WarehouseForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public null|Warehouse $warehouse = null;

    public function __construct(
        readonly private MessageBusInterface $bus,
    ) {
    }

    /**
     * @return FormInterface<WarehouseFormData>
     */
    protected function instantiateForm(): FormInterface
    {
        $data = new WarehouseFormData();

        if ($this->warehouse !== null) {
            $data->title = $this->warehouse->title;
        }

        return $this->createForm(WarehouseFormType::class, $data);
    }

    #[LiveAction]
    public function add(): Response
    {
        $this->submitForm();

        /** @var WarehouseFormData $data */
        $data = $this->getForm()->getData();

        $this->bus->dispatch(
            new AddWarehouse(
                $data->title,
            )
        );

        $this->addFlash('success', 'Sklad přidán');

        return $this->redirectToRoute('warehouses');
    }

    #[LiveAction]
    public function edit(): Response
    {
        $this->submitForm();

        /** @var WarehouseFormData $data */
        $data = $this->getForm()->getData();

        assert($this->warehouse !== null);

        $this->bus->dispatch(
            new EditWarehouse(
                $this->warehouse->id,
                $data->title,
            ),
        );

        $this->addFlash('success', 'Sklad upraven');

        return $this->redirectToRoute('warehouses');
    }
}
