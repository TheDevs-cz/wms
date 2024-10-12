<?php

declare(strict_types=1);

namespace TheDevs\WMS\Components\Warehouse;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
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
use TheDevs\WMS\FormData\WarehouseFormData;
use TheDevs\WMS\FormType\WarehouseFormType;
use TheDevs\WMS\Message\Warehouse\AddWarehouse;

#[AsLiveComponent]
#[IsGranted(User::ROLE_ADMIN)]
final class WarehouseForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public WarehouseFormData $data;

    public function __construct(
        readonly private MessageBusInterface $bus,
        readonly private Security $security,

    ) {
        $this->data = new WarehouseFormData();
    }

    /**
     * @return FormInterface<WarehouseFormData>
     */
    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(WarehouseFormType::class, $this->data);
    }

    #[LiveAction]
    public function add(): Response
    {
        $this->submitForm();

        /** @var User $user */
        $user = $this->security->getUser();

        $this->bus->dispatch(
            new AddWarehouse(
                $user->id,
                $this->data->title,
            )
        );

        $this->addFlash('success', 'Sklad přidán');

        return $this->redirectToRoute('warehouses');
    }
}
