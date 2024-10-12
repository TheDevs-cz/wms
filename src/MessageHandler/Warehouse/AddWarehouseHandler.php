<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Warehouse;

use Psr\Clock\ClockInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Entity\Warehouse;
use TheDevs\WMS\Exceptions\UserNotFound;
use TheDevs\WMS\Message\Warehouse\AddWarehouse;
use TheDevs\WMS\Repository\UserRepository;
use TheDevs\WMS\Repository\WarehouseRepository;
use TheDevs\WMS\Services\ProvideIdentity;

#[AsMessageHandler]
readonly final class AddWarehouseHandler
{
    public function __construct(
        private UserRepository $userRepository,
        private WarehouseRepository $warehouseRepository,
        private ClockInterface $clock,
        private ProvideIdentity $provideIdentity,
    ) {
    }

    /**
     * @throws UserNotFound
     */
    public function __invoke(AddWarehouse $message): void
    {
        $user = $this->userRepository->getById($message->userId);

        $warehouse = new Warehouse(
            $this->provideIdentity->next(),
            $user,
            $this->clock->now(),
            $message->title,
        );

        $this->warehouseRepository->save($warehouse);
    }
}
