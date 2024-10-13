<?php

declare(strict_types=1);

namespace TheDevs\WMS\Components\Location;

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
use TheDevs\WMS\Entity\Location;
use TheDevs\WMS\FormData\LocationFormData;
use TheDevs\WMS\FormType\LocationFormType;
use TheDevs\WMS\Message\Location\AddLocation;
use TheDevs\WMS\Message\Location\EditLocation;

#[AsLiveComponent]
#[IsGranted(User::ROLE_ADMIN)]
final class LocationForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public null|Location $location = null;

    public function __construct(
        readonly private MessageBusInterface $bus,
    ) {
    }

    /**
     * @return FormInterface<LocationFormData>
     */
    protected function instantiateForm(): FormInterface
    {
        $data = new LocationFormData();

        if ($this->location !== null) {
            $data->name = $this->location->name;
            $data->warehouse = $this->location->warehouse;
        }

        return $this->createForm(LocationFormType::class, $data);
    }

    #[LiveAction]
    public function handleAdd(): Response
    {
        $this->submitForm();

        /** @var LocationFormData $data */
        $data = $this->getForm()->getData();

        assert($data->warehouse !== null);

        $this->bus->dispatch(
            new AddLocation(
                $data->warehouse->id,
                $data->name,
            )
        );

        $this->addFlash('success', 'Lokace přidána');

        return $this->redirectToRoute('locations');
    }

    #[LiveAction]
    public function handleEdit(): Response
    {
        $this->submitForm();

        /** @var LocationFormData $data */
        $data = $this->getForm()->getData();

        assert($this->location !== null);
        assert($data->warehouse !== null);

        $this->bus->dispatch(
            new EditLocation(
                $this->location->id,
                $data->warehouse->id,
                $data->name,
            ),
        );

        $this->addFlash('success', 'Lokace upravena');

        return $this->redirectToRoute('locations');
    }
}
