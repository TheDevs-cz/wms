<?php

declare(strict_types=1);

namespace TheDevs\WMS\Components\BarcodeScanner;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
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
use TheDevs\WMS\Entity\StockItem;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\Exceptions\InsufficientStockItemQuantity;
use TheDevs\WMS\Exceptions\MultipleStockItemsFound;
use TheDevs\WMS\Exceptions\NoOrderItemFoundOnPosition;
use TheDevs\WMS\Exceptions\OrderItemAlreadyFullyPrepared;
use TheDevs\WMS\Exceptions\OrderItemNotFound;
use TheDevs\WMS\Exceptions\StockItemNotFound;
use TheDevs\WMS\FormData\BarcodeScanFormData;
use TheDevs\WMS\FormType\BarcodeScanFormType;
use TheDevs\WMS\Message\Order\PrepareOrderItem;
use TheDevs\WMS\Query\StockItemQuery;
use TheDevs\WMS\Repository\OrderRepository;
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
    public string|null $error = null;

    public function __construct(
        readonly private OrderRepository $orderRepository,
        readonly private MessageBusInterface $bus,
        readonly private StockItemQuery $stockItemQuery,
        readonly private Security $security,
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
            $positionId = ExtractUuid::fromUrl($code);

            if ($positionId !== null) {
                $code = $this->code;
            }

            $this->bus->dispatch(
                new PrepareOrderItem(
                    $user->id,
                    $orderId,
                    $code,
                    $positionId,
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
                MultipleStockItemsFound::class => sprintf('Zboží s EANem "%s" je na více pozicích.', $code),
                NoOrderItemFoundOnPosition::class => 'Na této pozici není žádné zboží z objednávky.',
                InsufficientStockItemQuantity::class => 'Není dostatek zboží - je potřeba naskladnit.',
                default => throw $e,
            };

            if ($e->getPrevious() instanceof MultipleStockItemsFound) {
                $this->code = $code;
            }
        }
    }

    /**
     * @return array<StockItem>
     */
    public function getPositionsForEan(): array
    {
        if ($this->code === null) {
            return [];
        }

        $user = $this->security->getUser();
        assert($user instanceof User);

        return $this->stockItemQuery->findAllByEanOfUser($this->code, $user->id);
    }
}
