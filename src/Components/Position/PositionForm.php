<?php

declare(strict_types=1);

namespace TheDevs\WMS\Components\Position;

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
use TheDevs\WMS\Entity\Position;
use TheDevs\WMS\FormData\PositionFormData;
use TheDevs\WMS\FormType\PositionFormType;
use TheDevs\WMS\Message\Position\AddPosition;
use TheDevs\WMS\Message\Position\EditPosition;

#[AsLiveComponent]
#[IsGranted(User::ROLE_ADMIN)]
final class PositionForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public null|Position $position = null;

    public function __construct(
        readonly private MessageBusInterface $bus,
    ) {
    }

    /**
     * @return FormInterface<PositionFormData>
     */
    protected function instantiateForm(): FormInterface
    {
        $data = new PositionFormData();

        if ($this->position !== null) {
            $data->name = $this->position->name;
            $data->location = $this->position->location;
        }

        return $this->createForm(PositionFormType::class, $data);
    }

    #[LiveAction]
    public function handleAdd(): Response
    {
        $this->submitForm();

        /** @var PositionFormData $data */
        $data = $this->getForm()->getData();

        assert($data->location !== null);

        $this->bus->dispatch(
            new AddPosition(
                $data->location->id,
                $data->name,
            )
        );

        $this->addFlash('success', 'Pozice přidána');

        return $this->redirectToRoute('positions');
    }

    #[LiveAction]
    public function handleEdit(): Response
    {
        $this->submitForm();

        /** @var PositionFormData $data */
        $data = $this->getForm()->getData();

        assert($this->position !== null);
        assert($data->location !== null);

        $this->bus->dispatch(
            new EditPosition(
                $this->position->id,
                $data->location->id,
                $data->name,
            ),
        );

        $this->addFlash('success', 'Pozice upravena');

        return $this->redirectToRoute('positions');
    }
}
