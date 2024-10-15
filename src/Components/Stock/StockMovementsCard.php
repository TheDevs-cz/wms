<?php

declare(strict_types=1);

namespace TheDevs\WMS\Components\Stock;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use TheDevs\WMS\Entity\Location;
use TheDevs\WMS\Entity\Position;
use TheDevs\WMS\Entity\Product;
use TheDevs\WMS\Entity\StockMovement;
use TheDevs\WMS\Entity\Warehouse;
use TheDevs\WMS\Query\StockMovementQuery;

#[AsLiveComponent]
final class StockMovementsCard extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp]
    public null|Product $product = null;

    #[LiveProp]
    public null|Position $position = null;

    #[LiveProp]
    public null|Warehouse $warehouse = null;

    #[LiveProp]
    public null|Location $location = null;

    public function __construct(
        readonly private StockMovementQuery $stockMovementQuery,
    ) {
    }

    /**
     * @return array<StockMovement>
     */
    public function getMovements(): array
    {
        if ($this->product !== null) {
            return $this->stockMovementQuery->getForProduct($this->product->id);
        }

        if ($this->position !== null) {
            return $this->stockMovementQuery->getForPosition($this->position->id);
        }

        if ($this->location !== null) {
            return $this->stockMovementQuery->getForLocation($this->location->id);
        }

        if ($this->warehouse !== null) {
            return $this->stockMovementQuery->getForWarehouse($this->warehouse->id);
        }

        return [];
    }
}
