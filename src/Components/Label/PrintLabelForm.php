<?php

declare(strict_types=1);

namespace TheDevs\WMS\Components\Label;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;

#[AsLiveComponent]
final class PrintLabelForm extends AbstractController
{
    use DefaultActionTrait;
    use ValidatableComponentTrait;

    #[LiveProp(writable: true)]
    #[NotBlank]
    #[Type('integer')]
    #[Range(min: 1, max: 10)]
    public int $packageCount = 1;

    #[LiveProp]
    public null|string $orderNumber = null;

    #[LiveAction]
    public function submit(): Response
    {
        $this->validate();

        return new RedirectResponse(
            sprintf(
                'https://api.services.omnicado.com/api/label/%s?packageCount=%d&newStatus=shipping',
                $this->orderNumber,
                $this->packageCount
            )
        );
    }
}
