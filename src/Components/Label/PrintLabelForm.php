<?php

declare(strict_types=1);

namespace TheDevs\WMS\Components\Label;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\Validator\Constraints\Type;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use Symfony\UX\LiveComponent\ValidatableComponentTrait;
use TheDevs\WMS\Entity\Order;
use TheDevs\WMS\Message\Order\DownloadOrderShippingLabel;
use TheDevs\WMS\Repository\OrderRepository;

#[AsLiveComponent]
final class PrintLabelForm extends AbstractController
{
    use DefaultActionTrait;
    use ValidatableComponentTrait;

    public function __construct(
        readonly private MessageBusInterface $bus,
        private readonly OrderRepository $orderRepository,
    ) {
    }

    #[LiveProp]
    public null|Order $order = null;

    #[LiveProp(writable: true)]
    #[NotBlank]
    #[Type('integer')]
    #[Range(min: 1, max: 10)]
    public int $packageCount = 1;

    #[LiveAction]
    public function submit()
    {
        $order = $this->order;
        assert($order !== null);

        $this->validate();

        if ($order->shippingLabel === null) {
            $this->bus->dispatch(
                new DownloadOrderShippingLabel($order->id, $this->packageCount),
            );

            $order = $this->orderRepository->get($order->id);
        }

        if ($order->shippingLabel !== null) {
            return new RedirectResponse($order->shippingLabel);
        }
    }
}
