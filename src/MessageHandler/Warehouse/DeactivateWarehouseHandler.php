<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Warehouse;

use Psr\Clock\ClockInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Exceptions\WarehouseNotFound;
use TheDevs\WMS\Message\Warehouse\DeactivateWarehouse;
use TheDevs\WMS\Repository\WarehouseRepository;

#[AsMessageHandler]
readonly final class DeactivateWarehouseHandler
{
    public function __construct(
        private WarehouseRepository $warehouseRepository,
        private ClockInterface $clock,
    ) {
    }

    /**
     * @throws WarehouseNotFound
     */
    public function __invoke(DeactivateWarehouse $message): void
    {
        $warehouse = $this->warehouseRepository->get($message->warehouseId);

        $warehouse->deactivate($this->clock->now());
    }
}
