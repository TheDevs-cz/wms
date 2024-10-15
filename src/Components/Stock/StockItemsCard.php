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
use TheDevs\WMS\Entity\StockItem;
use TheDevs\WMS\Query\StockItemQuery;

#[AsLiveComponent]
final class StockItemsCard extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp]
    public null|Position $position = null;

    #[LiveProp]
    public null|Location $location = null;

    #[LiveProp]
    public null|Product $product = null;

    public function __construct(
        readonly private StockItemQuery $stockItemQuery,
    ) {
    }

    /**
     * @return array<StockItem>
     */
    public function getStockItems(): array
    {
        if ($this->product !== null) {
            return $this->stockItemQuery->getForProduct($this->product->id);
        }

        if ($this->position !== null) {
            return $this->stockItemQuery->getForPosition($this->position->id);
        }

        if ($this->location !== null) {
            return $this->stockItemQuery->getForLocation($this->location->id);
        }

        return [];
    }
}
