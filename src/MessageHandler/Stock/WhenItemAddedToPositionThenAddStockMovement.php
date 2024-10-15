<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Stock;

use Psr\Clock\ClockInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Entity\StockMovement;
use TheDevs\WMS\Events\ItemAddedToPosition;
use TheDevs\WMS\Exceptions\PositionNotFound;
use TheDevs\WMS\Exceptions\ProductNotFound;
use TheDevs\WMS\Exceptions\UserNotFound;
use TheDevs\WMS\Query\ProductQuery;
use TheDevs\WMS\Repository\PositionRepository;
use TheDevs\WMS\Repository\StockMovementRepository;
use TheDevs\WMS\Repository\UserRepository;
use TheDevs\WMS\Services\ProvideIdentity;

#[AsMessageHandler]
readonly final class WhenItemAddedToPositionThenAddStockMovement
{
    public function __construct(
        private ProvideIdentity $provideIdentity,
        private ClockInterface $clock,
        private UserRepository $userRepository,
        private PositionRepository $positionRepository,
        private ProductQuery $productQuery,
        private StockMovementRepository $stockMovementRepository,
    ) {
    }

    /**
     * @throws PositionNotFound
     * @throws UserNotFound
     */
    public function __invoke(ItemAddedToPosition $event): void
    {
        $user = $this->userRepository->getById($event->byUserId);
        $position = $this->positionRepository->get($event->positionId);

        try {
            $product = $this->productQuery->getByEan($event->ean);
        } catch (ProductNotFound) {
            $product = null;
        }

        $movement = new StockMovement(
            $this->provideIdentity->next(),
            $user,
            $event->ean,
            null,
            0,
            $event->quantity,
            $product,
            null,
            $position,
            null,
            $this->clock->now(),
        );

        $this->stockMovementRepository->add($movement);
    }
}
