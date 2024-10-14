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
use TheDevs\WMS\Entity\Warehouse;
use TheDevs\WMS\FormData\GenerateLocationsFormData;
use TheDevs\WMS\FormType\GenerateLocationsFormType;
use TheDevs\WMS\Message\Location\GenerateLocations;

#[AsLiveComponent]
#[IsGranted(User::ROLE_ADMIN)]
final class GenerateLocationsForm extends AbstractController
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
     * @return FormInterface<GenerateLocationsFormData>
     */
    protected function instantiateForm(): FormInterface
    {
        $data = new GenerateLocationsFormData();

        if ($this->warehouse !== null) {
            $data->warehouse = $this->warehouse;
        }

        return $this->createForm(GenerateLocationsFormType::class, $data);
    }

    #[LiveAction]
    public function handleSubmit(): Response
    {
        $this->submitForm();

        /** @var GenerateLocationsFormData $data */
        $data = $this->getForm()->getData();

        assert($data->warehouse !== null);

        $this->bus->dispatch(
            new GenerateLocations(
                $data->warehouse->id,
                $data->pattern,
                $data->start,
                $data->end,
            )
        );

        $this->addFlash('success', 'Lokace vygenerovány');

        return $this->redirectToRoute('locations');
    }
}
