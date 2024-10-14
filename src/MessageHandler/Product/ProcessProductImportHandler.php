<?php

declare(strict_types=1);

namespace TheDevs\WMS\MessageHandler\Product;

use Psr\Clock\ClockInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use TheDevs\WMS\Entity\Product;
use TheDevs\WMS\Exceptions\ProductNotFound;
use TheDevs\WMS\Exceptions\UserNotFound;
use TheDevs\WMS\Message\Product\ProcessProductImport;
use TheDevs\WMS\Query\ProductQuery;
use TheDevs\WMS\Repository\ProductRepository;
use TheDevs\WMS\Repository\UserRepository;
use TheDevs\WMS\Services\ProvideIdentity;

#[AsMessageHandler]
readonly final class ProcessProductImportHandler
{
    public function __construct(
        private ProductRepository $productRepository,
        private UserRepository $userRepository,
        private ProductQuery $productQuery,
        private ProvideIdentity $provideIdentity,
        private ClockInterface $clock,
    ) {
    }

    /**
     * @throws UserNotFound
     */
    public function __invoke(ProcessProductImport $message): void
    {
        try {
            $product = $this->productQuery->searchByEan($message->userId, $message->ean);

            $product->edit(
                title: $message->title,
                category: $message->category,
                manufacturer: $message->manufacturer,
                image: $message->image,
            );
        } catch (ProductNotFound) {
            $user = $this->userRepository->getById($message->userId);

            $product = new Product(
                $this->provideIdentity->next(),
                $user,
                sku: $message->sku,
                ean: $message->ean,
                importedAt: $this->clock->now(),
                title: $message->title,
                category: $message->category,
                manufacturer: $message->manufacturer,
                image: $message->image,
            );

            $this->productRepository->add($product);
        }
    }
}
