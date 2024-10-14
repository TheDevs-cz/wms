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
use TheDevs\WMS\Entity\Location;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\FormData\GeneratePositionsFormData;
use TheDevs\WMS\FormType\GeneratePositionsFormType;
use TheDevs\WMS\Message\Position\GeneratePositions;

#[AsLiveComponent]
#[IsGranted(User::ROLE_ADMIN)]
final class GeneratePositionsForm extends AbstractController
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
     * @return FormInterface<GeneratePositionsFormData>
     */
    protected function instantiateForm(): FormInterface
    {
        $data = new GeneratePositionsFormData();

        if ($this->location !== null) {
            $data->location = $this->location;
        }

        return $this->createForm(GeneratePositionsFormType::class, $data);
    }

    #[LiveAction]
    public function handleSubmit(): Response
    {
        $this->submitForm();

        /** @var GeneratePositionsFormData $data */
        $data = $this->getForm()->getData();

        assert($data->location !== null);

        $this->bus->dispatch(
            new GeneratePositions(
                $data->location->id,
                $data->pattern,
                $data->start,
                $data->end,
            )
        );

        $this->addFlash('success', 'Pozice vygenerovány');

        return $this->redirectToRoute('positions');
    }
}
