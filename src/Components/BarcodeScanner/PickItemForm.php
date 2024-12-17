<?php

declare(strict_types=1);

namespace TheDevs\WMS\Components\BarcodeScanner;

use Ramsey\Uuid\Uuid;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Messenger\Exception\HandlerFailedException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use TheDevs\WMS\Entity\Order;
use TheDevs\WMS\Entity\Position;
use TheDevs\WMS\Entity\StockItem;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\Exceptions\InsufficientStockItemQuantity;
use TheDevs\WMS\Exceptions\MultipleOrderItemsFoundOnPosition;
use TheDevs\WMS\Exceptions\MultipleStockItemsFound;
use TheDevs\WMS\Exceptions\NoOrderItemFoundOnPosition;
use TheDevs\WMS\Exceptions\OrderItemAlreadyFullyPrepared;
use TheDevs\WMS\Exceptions\OrderItemNotFound;
use TheDevs\WMS\Exceptions\StockItemNotFound;
use TheDevs\WMS\FormData\BarcodeScanFormData;
use TheDevs\WMS\FormType\BarcodeScanFormType;
use TheDevs\WMS\Message\Order\PrepareOrderItem;
use TheDevs\WMS\Query\PositionQuery;
use TheDevs\WMS\Query\StockItemQuery;
use TheDevs\WMS\Repository\OrderRepository;
use TheDevs\WMS\Repository\PositionRepository;
use TheDevs\WMS\Services\ExtractUuid;

#[AsLiveComponent]
final class PickItemForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public null|Order $order = null;

    #[LiveProp]
    public null|string $code = null;

    #[LiveProp]
    public null|string $positionId = null;

    #[LiveProp]
    public string|null $error = null;

    public function __construct(
        readonly private OrderRepository $orderRepository,
        readonly private MessageBusInterface $bus,
        readonly private StockItemQuery $stockItemQuery,
        readonly private PositionRepository $positionRepository,
    ) {
    }

    /**
     * @return FormInterface<BarcodeScanFormData>
     */
    protected function instantiateForm(): FormInterface
    {
        $data = new BarcodeScanFormData();

        return $this->createForm(BarcodeScanFormType::class, $data);
    }

    #[LiveAction]
    public function handleScan(
        #[CurrentUser] User $user,
    )
    {
        $this->submitForm();

        assert($this->order !== null);
        $orderId = $this->order->id;

        /** @var BarcodeScanFormData $data */
        $data = $this->getForm()->getData();

        $code = $data->code;

        try {
            $positionId = ExtractUuid::fromUrl($code)?->toString();

            if ($positionId !== null) {
                $this->positionId = $positionId;
                $code = $this->code;
            }

            $this->bus->dispatch(
                new PrepareOrderItem(
                    $user->id,
                    $orderId,
                    $code,
                    $this->positionId !== null ? Uuid::fromString($this->positionId) : null,
                ),
            );

            $order = $this->orderRepository->get($orderId);

            if ($order->isFullyPicked()) {
                $this->addFlash('success', 'Objednávka byla plně vychystána.');

                return $this->redirectToRoute('order_detail', ['id' => $orderId]);
            }

            $this->addFlash('success', 'Zboží bylo vychystáno.');

            return $this->redirectToRoute('order_prepare_item', ['id' => $orderId]);
        } catch (HandlerFailedException $e) {
            $this->code = null;
            $this->error = match (get_class($e->getPrevious() ?? $e)) {
                OrderItemAlreadyFullyPrepared::class => 'Položka objednávky již byla plně vychystána.',
                StockItemNotFound::class => sprintf('Zboží s EANem "%s" není naskladněné.', $code),
                OrderItemNotFound::class => sprintf('Zboží s EANem "%s" není v této objednávce.', $code),
                MultipleStockItemsFound::class => sprintf('Zboží s EANem "%s" je na více pozicích - nyní načtěte pozici', $code),
                NoOrderItemFoundOnPosition::class => 'Na této pozici není žádné zboží z objednávky.',
                InsufficientStockItemQuantity::class => 'Není dostatek zboží - je potřeba naskladnit.',
                MultipleOrderItemsFoundOnPosition::class => 'Na této pozici je více položek - nyní načtěte EAN položky',
                default => throw $e,
            };

            if ($e->getPrevious() instanceof MultipleStockItemsFound) {
                $this->code = $code;
            }

            if ($e->getPrevious() instanceof InsufficientStockItemQuantity) {
                $this->positionId = null;
            }
        }
    }

    /**
     * @return array<StockItem>
     */
    public function getStockItemsForEan(): array
    {
        if ($this->code === null) {
            return [];
        }

        assert($this->order !== null);

        return $this->stockItemQuery->findAllByEanOfUser($this->code, $this->order->user->id);
    }

    public function getPosition(): null|Position
    {
        if ($this->positionId === null) {
            return null;
        }

        return $this->positionRepository->get(
            Uuid::fromString($this->positionId),
        );
    }
}
