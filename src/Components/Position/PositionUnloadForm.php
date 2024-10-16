<?php

declare(strict_types=1);

namespace TheDevs\WMS\Components\Position;

use Psr\Clock\ClockInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveAction;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use TheDevs\WMS\Entity\Position;
use TheDevs\WMS\Entity\Product;
use TheDevs\WMS\Entity\User;
use TheDevs\WMS\FormData\PositionStockFormData;
use TheDevs\WMS\FormType\PositionStockFormType;
use TheDevs\WMS\Message\Stock\UnloadStockItem;
use TheDevs\WMS\Query\ProductQuery;

#[AsLiveComponent]
final class PositionUnloadForm extends AbstractController
{
    use DefaultActionTrait;
    use ComponentWithFormTrait;

    #[LiveProp]
    public null|Position $position = null;

    #[LiveProp]
    public null|Product $scannedProduct = null;

    /** @var array<string> */
    #[LiveProp(writable: true)]
    public array $recentScans = [];

    public function __construct(
        readonly private MessageBusInterface $bus,
        readonly private ClockInterface $clock,
        readonly private ProductQuery $productQuery,
    ) {
    }

    /**
     * @return FormInterface<PositionStockFormData>
     */
    protected function instantiateForm(): FormInterface
    {
        $data = new PositionStockFormData();

        return $this->createForm(PositionStockFormType::class, $data);
    }

    #[LiveAction]
    public function handleSubmit(
        #[CurrentUser] User $user,
    ): void {
        $this->submitForm();

        /** @var PositionStockFormData $data */
        $data = $this->getForm()->getData();

        $position = $this->position;
        assert($position !== null);

        $now = $this->clock->now();

        $this->bus->dispatch(
            new UnloadStockItem(
                $user->id,
                $position->id,
                $data->code,
                $data->quantity,
            )
        );

        $log = sprintf('%s / %s / %s / %s ks', $now->format('H:i:s'), $position->name, $data->code, $data->quantity);
        array_unshift($this->recentScans, $log);

        $this->scannedProduct = $this->productQuery->getByEan($data->code);

        if (count($this->recentScans) > 5) {
            $this->recentScans = array_slice($this->recentScans, 0, 5);
        }

        $this->resetForm();
    }
}
