<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Warehouse;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Exceptions\WarehouseNotFound;
use TheDevs\WMS\Message\Warehouse\EditWarehouse;
use TheDevs\WMS\Repository\WarehouseRepository;

#[AsMessageHandler]
readonly final class EditWarehouseHandler
{
    public function __construct(
        private WarehouseRepository $warehouseRepository,
    ) {
    }

    /**
     * @throws WarehouseNotFound
     */
    public function __invoke(EditWarehouse $message): void
    {
        $warehouse = $this->warehouseRepository->get($message->warehouseId);

        $warehouse->edit($message->title);
    }
}
