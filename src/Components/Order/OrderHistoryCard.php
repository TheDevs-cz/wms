<?php

declare(strict_types=1);

namespace TheDevs\WMS\Components\Order;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;
use TheDevs\WMS\Entity\Order;
use TheDevs\WMS\Entity\OrderHistory;
use TheDevs\WMS\Query\OrderHistoryQuery;

#[AsLiveComponent]
final class OrderHistoryCard extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp]
    public null|Order $order = null;

    public function __construct(
        readonly private OrderHistoryQuery $orderHistoryQuery,
    ) {
    }

    /**
     * @return array<OrderHistory>
     */
    public function getHistory(): array
    {
        if ($this->order !== null) {
            return $this->orderHistoryQuery->getForOrder($this->order->id);
        }

        return $this->orderHistoryQuery->getAll();
    }
}
